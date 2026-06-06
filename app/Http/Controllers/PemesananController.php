<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PemesananController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Display pemesanan dashboard with orders by status
     */
    public function index(Request $request)
    {
        try {
            $perPage = 4; // Display 4 items per page per tab as in original design
            $search = $request->get('search', null);

            // Base query
            $baseQuery = function($status) use ($search) {
                $query = Order::where('status', $status)
                    ->with('items.product', 'payment', 'delivery')
                    ->orderBy('created_at', 'desc');

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('invoice_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%");
                    });
                }

                return $query;
            };

            // Get paginated orders by status
            $pesananBaru = $baseQuery('unpaid')->paginate($perPage, ['*'], 'page_baru');
            $perluDikirim = $baseQuery('paid')->paginate($perPage, ['*'], 'page_dikirim');
            $sedangDikirim = $baseQuery('shipping')->paginate($perPage, ['*'], 'page_sedang');
            $selesai = $baseQuery('completed')->paginate($perPage, ['*'], 'page_selesai');

            return view('admin.pemesanan', [
                'pesananBaru' => $pesananBaru,
                'perluDikirim' => $perluDikirim,
                'sedangDikirim' => $sedangDikirim,
                'selesai' => $selesai,
                'search' => $search,
            ]);
        } catch (\Exception $e) {
            \Log::error('Pemesanan view error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load orders');
        }
    }

    /**
     * Handle process shipment for pengiriman section
     */
    public function processShipment(Request $request, Order $order): JsonResponse
    {
        try {
            // Check payment status
            $payment = $order->payment;
            
            if (!$payment || $payment->status !== 'settlement') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not confirmed yet',
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

            // Update order status to shipping (after Biteship shipment created)
            $order->status = 'shipping';
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
     * Cancel an order
     */
    public function cancelOrder(Request $request, Order $order): JsonResponse
    {
        try {
            // Update order status to cancelled
            $order->status = 'cancelled';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('Cancel order error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order',
            ], 500);
        }
    }
}
