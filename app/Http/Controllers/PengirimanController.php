<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Delivery;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PengirimanController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Display all deliveries with pagination
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $status = $request->input('status', null);
            $search = $request->input('search', null);

            $query = Order::with('payment', 'delivery', 'items')
                ->whereIn('status', ['paid', 'packing', 'shipping', 'completed']);

            // Filter by delivery status if provided
            if ($status) {
                $query->whereHas('delivery', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            }

            // Search by order ID or customer name
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                });
            }

            $orders = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return view('admin.pengiriman', [
                'orders' => $orders,
                'currentStatus' => $status,
                'searchQuery' => $search,
            ]);
        } catch (\Exception $e) {
            \Log::error('Pengiriman view error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load deliveries');
        }
    }

    /**
     * Get awaiting payment orders (menunggu pembayaran)
     */
    public function awaitingPayment(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);

            $orders = Order::where('status', 'unpaid')
                ->with('payment', 'items')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $orders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch awaiting payment orders',
            ], 500);
        }
    }

    /**
     * Create shipment for order
     */
    public function createShipment(Request $request, Order $order): JsonResponse
    {
        try {
            // Validate order is paid and not yet shipped
            if ($order->status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order must be paid before shipping',
                ], 422);
            }

            if ($order->delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shipment already created for this order',
                ], 422);
            }

            // Create shipment in Biteship
            $delivery = $this->shippingService->createShipment($order);

            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create shipment',
                ], 500);
            }

            // Update order status to packing
            $order->status = 'packing';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Shipment created successfully',
                'delivery' => $delivery,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Shipment creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create shipment',
            ], 500);
        }
    }

    /**
     * Get available couriers for shipment
     */
    public function getAvailableCouriers(Order $order): JsonResponse
    {
        try {
            $couriers = $this->shippingService->getAvailableCouriers(
                $order->postal_code,
                1000
            );

            if (!$couriers) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch available couriers',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => $couriers,
            ]);
        } catch (\Exception $e) {
            \Log::error('Get couriers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch couriers',
            ], 500);
        }
    }

    /**
     * Update courier and service for shipment
     */
    public function updateCourier(Request $request, Order $order): JsonResponse
    {
        try {
            $validated = $request->validate([
                'courier_code' => 'required|string',
                'service_code' => 'required|string',
            ]);

            if (!$order->delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'No shipment found for this order',
                ], 404);
            }

            $result = $this->shippingService->updateShipmentCourier(
                $order->delivery->biteship_order_id,
                $validated['courier_code'],
                $validated['service_code']
            );

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update courier',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Courier updated successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update courier',
            ], 500);
        }
    }

    /**
     * Get shipment status
     */
    public function getStatus(Delivery $delivery): JsonResponse
    {
        try {
            $status = $this->shippingService->getShipmentStatus($delivery->biteship_order_id);

            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch shipment status',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => $status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch status',
            ], 500);
        }
    }

    /**
     * Get tracking timeline and sync with Biteship API
     */
    public function showTracking(Delivery $delivery): JsonResponse
    {
        try {
            $order = $delivery->order;
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan',
                ], 404);
            }

            if ($delivery->biteship_order_id) {
                $biteshipData = $this->shippingService->getShipmentStatus($delivery->biteship_order_id);
                if ($biteshipData && isset($biteshipData['success']) && $biteshipData['success'] && isset($biteshipData['data'])) {
                    $data = $biteshipData['data'];
                    $delivery->status = $data['status'] ?? $delivery->status;
                    
                    if (isset($data['courier'])) {
                        $delivery->tracking_number = $data['courier']['tracking_number'] ?? $delivery->tracking_number;
                        $delivery->courier_driver_name = $data['courier']['driver_name'] ?? $delivery->courier_driver_name;
                        $delivery->tracking_link = $data['courier']['link'] ?? $delivery->tracking_link;
                    }
                    
                    $delivery->save();

                    if ($delivery->status === 'on_delivery' || $delivery->status === 'dropping_off') {
                        $order->status = 'shipping';
                    } elseif ($delivery->status === 'delivered') {
                        $order->status = 'completed';
                    }
                    $order->save();

                    if (isset($data['history']) && is_array($data['history'])) {
                        foreach ($data['history'] as $historyItem) {
                            $status = $historyItem['status'] ?? '';
                            $note = $historyItem['note'] ?? '';
                            $updatedAt = isset($historyItem['updated_at']) ? \Carbon\Carbon::parse($historyItem['updated_at']) : now();
                            
                            $exists = \App\Models\DeliveryHistory::where('delivery_id', $delivery->id)
                                ->where('status', $status)
                                ->where('note', $note)
                                ->exists();
                                
                            if (!$exists) {
                                \App\Models\DeliveryHistory::create([
                                    'delivery_id' => $delivery->id,
                                    'status' => $status,
                                    'note' => $note,
                                    'created_at' => $updatedAt,
                                    'updated_at' => $updatedAt,
                                ]);
                            }
                        }
                    }
                }
            }

            $items = $order->items?->count() > 0 
                ? $order->items->map(fn($i) => "{$i->quantity}x {$i->product->name}")->implode(', ')
                : 'Pesanan tanpa item';

            $statusStepMap = [
                'draft' => 0,
                'created' => 0,
                'order_received' => 0,
                'confirmed' => 0,
                'allocated' => 0,
                'preparing' => 0,
                'picking_up' => 1,
                'picked' => 1,
                'picked_up' => 1,
                'in_transit' => 2,
                'transit' => 2,
                'dropping_off' => 3,
                'on_delivery' => 3,
                'delivered' => 4,
            ];

            $currentStepIndex = $statusStepMap[strtolower($delivery->status)] ?? 0;

            $stepDates = [
                0 => $order->created_at?->format('d M, H:i') ?? '-',
                1 => '-',
                2 => '-',
                3 => '-',
                4 => '-',
            ];

            $histories = $delivery->histories()->orderBy('created_at', 'asc')->get();
            foreach ($histories as $history) {
                $mappedStepIdx = $statusStepMap[strtolower($history->status)] ?? 0;
                $stepDates[$mappedStepIdx] = $history->created_at?->format('d M, H:i') ?? '-';
            }

            foreach ($stepDates as $idx => $date) {
                if ($date !== '-' && $idx > $currentStepIndex) {
                    $currentStepIndex = $idx;
                }
            }

            $allSteps = [];
            $stepNames = [
                0 => 'Order Dikonfirmasi',
                1 => 'Dikirim dari Gudang',
                2 => 'Tiba di Transit',
                3 => 'Out for Delivery',
                4 => 'Terkirim',
            ];

            for ($i = 0; $i < 5; $i++) {
                $allSteps[] = [
                    'name' => $stepNames[$i],
                    'date' => $stepDates[$i],
                    'completed' => $i < $currentStepIndex || ($currentStepIndex === 4 && $i === 4),
                    'active' => $i === $currentStepIndex && $currentStepIndex !== 4,
                ];
            }

            $trackingHistory = array_slice($allSteps, 0, $currentStepIndex + 1);

            return response()->json([
                'success' => true,
                'data' => [
                    'invoice_number' => $order->invoice_number ?? '-',
                    'customer_name' => $order->customer_name ?? '-',
                    'order_items' => $items,
                    'tracking_number' => $delivery->tracking_number ?? '-',
                    'courier_company' => $delivery->courier_name ?? 'N/A',
                    'status' => $delivery->status ?? 'preparing',
                    'courier_driver_name' => $delivery->courier_driver_name ?? null,
                    'tracking_link' => $delivery->tracking_link ?? null,
                    'estimated_delivery' => 'Hari ini, 14:30 - 15:30',
                    'tracking_history' => $trackingHistory,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Tracking modal fetch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat tracking',
            ], 500);
        }
    }

    /**
     * Handle shipment webhook from Biteship
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        try {
            $payload = $request->all();
            
            // Accept empty body during Biteship installation/verification
            if (empty($payload)) {
                \Log::info('Biteship webhook verification request received');
                return response()->json(['success' => true], 200);
            }
            
            // Only validate signature in production
            if (app()->isProduction()) {
                $signature = $request->header('X-Biteship-Signature');
                $body = $request->getContent();
                $expected = hash_hmac('sha256', $body, config('services.biteship.api_key'));
                
                if (!hash_equals($expected, $signature ?? '')) {
                    \Log::warning('Invalid Biteship webhook signature');
                    return response()->json(['success' => false], 401);
                }
            }
            
            $result = $this->shippingService->processWebhook($payload);
            return response()->json(['success' => $result], 200);
        } catch (\Exception $e) {
            \Log::error('Shipment webhook error: ' . $e->getMessage());
            return response()->json(['success' => false], 200);
        }
    }

    /**
     * Process shipment for paid order (send to Biteship)
     */
    public function processShipment(Request $request, Order $order): JsonResponse
    {
        try {
            // Check payment status from Midtrans first
            $payment = $order->payment;
            
            if (!$payment || $payment->status !== 'settlement') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not confirmed yet',
                ], 422);
            }

            // Create shipment
            $delivery = $this->shippingService->createShipment($order);

            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create shipment',
                ], 500);
            }

            // Update order status
            $order->status = 'packing';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Shipment processed successfully',
                'delivery' => $delivery,
            ]);
        } catch (\Exception $e) {
            \Log::error('Process shipment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process shipment',
            ], 500);
        }
    }

    /**
     * Manually update delivery status (for testing/debugging)
     * Simulates Biteship webhook status updates
     */
    public function updateDeliveryStatus(Request $request, Delivery $delivery): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:preparing,requesting_pickup,on_pickup,picked_up,on_delivery,delivered,failed,cancelled',
            ]);

            $delivery->update([
                'status' => $validated['status'],
                'updated_at' => now(),
            ]);

            \App\Models\DeliveryHistory::create([
                'delivery_id' => $delivery->id,
                'status' => $validated['status'],
                'note' => $this->shippingService->getFriendlyStatusNote($validated['status']),
            ]);

            \Log::info("Delivery {$delivery->id} status updated to {$validated['status']} (manual test)");

            return response()->json([
                'success' => true,
                'message' => "Delivery status updated to {$validated['status']}",
                'delivery' => $delivery,
            ]);
        } catch (\Exception $e) {
            \Log::error('Update delivery status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update delivery status',
            ], 500);
        }
    }

    /**
     * cari area postal kode
     */
    public function searchArea(Request $request): JsonResponse
    {
        $query = $request->query('query');
        if (strlen($query) < 3) {
            return response()->json(['areas' => []]);
        }
        $result = $this->shippingService->searchArea($query);
        return response()->json($result ?? ['areas' => []]);
    }

    /**
     * estimasi ongkos kirim berdasarkan kode pos
     */
    public function estimateShipping(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'postal_code' => 'required|string|regex:/^[0-9]{5}$/',
            ]);

            $postalCode = $validated['postal_code'];
            $productName = session('checkout_product');
            $price = session('checkout_price');
            $quantity = session('checkout_quantity');

            if (!$productName || is_null($price) || !$quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi checkout tidak ditemukan. Silakan pilih produk kembali.'
                ], 400);
            }

            $areaResult = $this->shippingService->searchArea($postalCode);
            $isValidPostalCode = false;

            if (isset($areaResult['areas']) && is_array($areaResult['areas'])) {
                foreach ($areaResult['areas'] as $area) {
                    if (isset($area['postal_code']) && (int)$area['postal_code'] === (int)$postalCode) {
                        $isValidPostalCode = true;
                        break;
                    }
                }
                if (!$isValidPostalCode && count($areaResult['areas']) > 0) {
                    $isValidPostalCode = true;
                }
            }

            if (!$isValidPostalCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode pos tidak ditemukan di sistem Biteship.'
                ], 422);
            }

            $rates = $this->shippingService->calculateRates(
                $postalCode,
                $productName,
                $price,
                $quantity
            );

            $shippingCost = null;
            if ($rates && isset($rates['pricing']) && is_array($rates['pricing'])) {
                foreach ($rates['pricing'] as $pricing) {
                    $courierCode = strtolower($pricing['courier_code'] ?? '');
                    $serviceCode = strtolower($pricing['courier_service_code'] ?? $pricing['service_code'] ?? $pricing['service'] ?? '');
                    
                    if ($courierCode === 'jnt' && $serviceCode === 'ez') {
                        $shippingCost = $pricing['price'];
                        break;
                    }
                }

                if (is_null($shippingCost)) {
                    foreach ($rates['pricing'] as $pricing) {
                        $courierCode = strtolower($pricing['courier_code'] ?? '');
                        if ($courierCode === 'jnt') {
                            $shippingCost = $pricing['price'];
                            break;
                        }
                    }
                }
            }

            if (is_null($shippingCost)) {
                \Log::warning("Biteship rates calculation failed for postal code {$postalCode} (e.g., insufficient balance). Using fallback flat rate.");
                $shippingCost = 12000;
            }

            return response()->json([
                'success' => true,
                'shipping_cost' => $shippingCost
            ]);
        } catch (\Exception $e) {
            \Log::error('Shipping estimation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghitung ongkos kirim: ' . $e->getMessage()
            ], 500);
        }
    }
}
