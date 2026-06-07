<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.biteship.api_key');
        $this->apiUrl = config('services.biteship.api_url', 'https://api.biteship.com');
    }

    /**
     * Create shipment in Biteship
     */
    public function createShipment(Order $order): ?Delivery
    {
        try {
            $payload = $this->buildShipmentPayload($order);
            
            $response = $this->makeRequest('/v1/orders', $payload);

            if ($response && isset($response['id'])) {
                $delivery = new Delivery([
                    'order_id' => $order->id,
                    'biteship_order_id' => $response['id'],
                    'tracking_number' => $response['tracking_number'] ?? null,  // <- Add this
                    'courier_name' => $response['courier']['name'] ?? null,
                    'status' => $response['status'] ?? 'preparing',
                ]);
                if ($delivery->save()) {
                    \App\Models\DeliveryHistory::create([
                        'delivery_id' => $delivery->id,
                        'status' => $delivery->status,
                        'note' => $this->getFriendlyStatusNote($delivery->status),
                    ]);
                    return $delivery;
                }
            }

            return null;
        } catch (Exception $e) {
            \Log::error('Biteship shipment creation error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get shipment status from Biteship
     */
    public function getShipmentStatus(string $biteshipOrderId): ?array
    {
        try {
            $response = $this->makeRequest("/v1/orders/{$biteshipOrderId}", null, 'GET');
            return $response;
        } catch (Exception $e) {
            \Log::error('Biteship status check error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get available couriers
     */
    public function getAvailableCouriers(string $postalCode, int $weight = 1000): ?array
    {
        try {
            $params = [
                'destination_postal_code' => $postalCode,
                'weight' => $weight,
            ];

            $response = $this->makeRequest('/v1/couriers', $params, 'GET');
            return $response;
        } catch (Exception $e) {
            \Log::error('Biteship couriers error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update shipment with courier and service
     */
    public function updateShipmentCourier(string $biteshipOrderId, string $courierCode, string $serviceCode): ?array
    {
        try {
            $payload = [
                'courier_code' => $courierCode,
                'service_code' => $serviceCode,
            ];

            $response = $this->makeRequest("/v1/orders/{$biteshipOrderId}", $payload, 'PATCH');
            return $response;
        } catch (Exception $e) {
            \Log::error('Biteship update courier error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process webhook from Biteship
     */
    public function processWebhook(array $payload): bool
    {
        try {
            Log::info('Biteship webhook received:', $payload);
        
            $biteshipOrderId = $payload['order_id'] ?? null;
            $status = $payload['status'] ?? null;
            $trackingNumber = $payload['courier_tracking_id'] ?? null;
            $courierName = $payload['courier_company'] ?? null;
            $driverName = $payload['courier_driver_name'] ?? null;
            $trackingLink = $payload['courier_link'] ?? null;

            if (!$biteshipOrderId) {
                Log::warning('No order_id in webhook payload');
                return false;
            }

            $delivery = Delivery::where('biteship_order_id', $biteshipOrderId)->first();
            if (!$delivery) {
                Log::warning("Delivery not found for biteship_order_id: {$biteshipOrderId}");
                return false;
            }

            $oldStatus = $delivery->status;
            $newStatus = $payload['status'] ?? $status ?? $delivery->status;

            $delivery->courier_name = $courierName;
            $delivery->tracking_number = $trackingNumber;
            $delivery->status = $newStatus;
            $delivery->courier_driver_name = $driverName;
            $delivery->tracking_link = $trackingLink;
            $delivery->save();

            $exists = \App\Models\DeliveryHistory::where('delivery_id', $delivery->id)
                ->where('status', $newStatus)
                ->exists();

            if (!$exists) {
                $note = $payload['note'] ?? $this->getFriendlyStatusNote($newStatus);
                \App\Models\DeliveryHistory::create([
                    'delivery_id' => $delivery->id,
                    'status' => $newStatus,
                    'note' => $note,
                ]);
            }

            $this->updateOrderStatusFromDelivery($delivery);
            Log::info("Delivery updated: {$delivery->id} -> {$delivery->status}");

            return true;
        } catch (Exception $e) {
            Log::error('Biteship webhook error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Build shipment payload for Biteship
     */
    protected function buildShipmentPayload(Order $order): array
    {
        $items = $order->items->map(function ($item) {
            return [
                'name' => $item->product->name ?? 'Product',
                'description' => $item->product->description ?? '',
                'value' => $item->unit_price,
                'quantity' => $item->quantity,
            ];
        })->toArray();

        return [
            'origin_contact_name' => 'Cireng A\'Paweh',
            'origin_contact_phone' => '08123456789',
            'origin_address' => 'Tel U Cabang',
            'origin_postal_code' => 40257,
            'destination_contact_name' => $order->customer_name,
            'destination_contact_phone' => $order->customer_phone,
            'destination_address' => $order->shipping_address,
            'destination_postal_code' => (int)($order->postal_code),
            'courier_company' => 'jnt',
            'courier_type' => 'ez',
            'courier_service' => 'ez',
            'delivery_type' => 'now',
            'items' => $items,
        ];
    }

    /**
     * Create or update delivery record
     */
    protected function createDeliveryRecord(Order $order, array $biteshipData): Delivery
    {
        $delivery = $order->delivery ?? new Delivery();
        $delivery->order_id = $order->id;
        $delivery->biteship_order_id = $biteshipData['id'];
        $delivery->status = $this->mapBiteshipStatus($biteshipData['status'] ?? 'draft');
        $delivery->save();

        return $delivery;
    }

    /**
     * Map Biteship status to our delivery status
     */
    public function mapBiteshipStatus(string $status): string
    {
        $statusMap = [
            'draft' => 'preparing',
            'created' => 'preparing',
            'order_received' => 'preparing',
            'picked_up' => 'picked_up',
            'dropping_off' => 'on_delivery',
            'on_delivery' => 'on_delivery',
            'delivered' => 'delivered',
            'rejected' => 'preparing',
        ];

        return $statusMap[$status] ?? 'preparing';
    }

    /**
     * Update order status based on delivery status
     */
    protected function updateOrderStatusFromDelivery(Delivery $delivery): void
    {
        $order = $delivery->order;

        if ($delivery->status === 'on_delivery') {
            $order->status = 'shipping';
        } elseif ($delivery->status === 'delivered') {
            $order->status = 'completed';
        }

        $order->save();
    }

    /**
     * Get friendly note for Biteship status
     */
    public function getFriendlyStatusNote(string $status): string
    {
        $notes = [
            'confirmed' => 'Pesanan telah dikonfirmasi oleh kurir',
            'allocated' => 'Kurir telah dialokasikan, menunggu penjemputan',
            'picking_up' => 'Kurir sedang dalam perjalanan untuk menjemput paket',
            'picked' => 'Paket telah diserahkan kepada kurir',
            'picked_up' => 'Paket telah diambil oleh kurir dan dikirim dari gudang',
            'in_transit' => 'Paket sedang dalam perjalanan transit',
            'dropping_off' => 'Kurir sedang dalam perjalanan untuk mengantarkan paket ke alamat tujuan',
            'on_delivery' => 'Paket sedang dibawa oleh kurir menuju alamat tujuan',
            'delivered' => 'Paket telah berhasil diterima',
            'cancelled' => 'Pengiriman dibatalkan',
            'rejected' => 'Pengiriman ditolak',
        ];
        return $notes[strtolower($status)] ?? 'Status pengiriman: ' . $status;
    }

    /**
     * Make HTTP request to Biteship API
     */
    protected function makeRequest(string $endpoint, ?array $data = null, string $method = 'POST'): ?array
    {
        try {
            $url = $this->apiUrl . $endpoint;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30);

            /* skip verifikasi SSL buat localhost */
            if (app()->environment('local')) {
                $response = $response->withoutVerifying();
            }

            if ($method === 'GET') {
                $response = $response->get($url, $data ?? []);
            } elseif ($method === 'PATCH') {
                $response = $response->patch($url, $data ?? []);
            } else {
                $response = $response->post($url, $data ?? []);
            }

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Biteship API error: ' . $response->status() . ' - ' . $response->body());
            return null;
        } catch (Exception $e) {
            Log::error('Biteship request error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * cari area postal kode
     */
    public function searchArea(string $query): ?array
    {
        return $this->makeRequest('/v1/maps/areas', [
            'countries' => 'ID',
            'input' => $query
        ], 'GET');
    }

    /**
     * hitung ongkir
     */
    public function calculateRates(string $destinationPostalCode, string $productName, float $price, int $quantity): ? array
    {
        $payload = [
            'origin_postal_code' => 40257,
            'destination_postal_code' => (int) $destinationPostalCode,
            'couriers' => 'jnt',
            'items' => [
                [
                    'name' => $productName,
                    'value' => (int) $price,
                    'weight' => 200,
                    'quantity' => $quantity,
                ]
            ]
        ];

        return $this->makeRequest('/v1/rates/couriers', $payload, 'POST');
    }
}
