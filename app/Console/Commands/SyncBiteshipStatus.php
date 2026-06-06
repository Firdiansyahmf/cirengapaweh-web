<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use App\Models\Order;
use App\Services\ShippingService;
use Illuminate\Console\Command;

class SyncBiteshipStatus extends Command
{
    protected $signature = 'biteship:sync';
    protected $description = 'Sync delivery status from Biteship API';
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        parent::__construct();
        $this->shippingService = $shippingService;
    }

    public function handle()
    {
        $deliveries = Delivery::whereNotNull('biteship_order_id')
            ->where('status', '!=', 'delivered')
            ->get();

        if ($deliveries->isEmpty()) {
            $this->info('No pending deliveries to sync.');
            return 0;
        }

        foreach ($deliveries as $delivery) {
            $status = $this->shippingService->getShipmentStatus($delivery->biteship_order_id);

            if (!$status) {
                $this->warn("Failed to fetch status for delivery #{$delivery->id}");
                continue;
            }

            $oldStatus = $delivery->status;
            $delivery->status = $this->shippingService->mapBiteshipStatus($status['status'] ?? 'draft');
            
            if (isset($status['tracking_number'])) {
                $delivery->tracking_number = $status['tracking_number'];
            }
            if (isset($status['courier'])) {
                $delivery->courier_name = $status['courier']['name'] ?? null;
            }

            $delivery->save();

            $order = $delivery->order;
            if ($delivery->status === 'on_delivery') {
                $order->status = 'shipping';
            } elseif ($delivery->status === 'delivered') {
                $order->status = 'completed';
            }
            $order->save();

            if ($oldStatus !== $delivery->status) {
                $this->line("✓ Order {$order->invoice_number}: {$oldStatus} → {$delivery->status}");
            }
        }

        $this->info('Biteship status sync completed.');
        return 0;
    }
}
