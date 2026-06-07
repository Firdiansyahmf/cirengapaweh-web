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
            $perPage = 4; 
            $search = $request->get('search', null);

            $baseQuery = function($status) use ($search) {
                $query = Order::query()
                    ->with('items.product', 'payment', 'delivery')
                    ->orderBy('created_at', 'desc');

                if (is_array($status)) {
                    $query->whereIn('status', $status);
                } else {
                    $query->where('status', $status);
                }

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('invoice_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%");
                    });
                }

                return $query;
            };

            $pesananBaru = $baseQuery(['unpaid', 'paid'])->paginate($perPage, ['*'], 'page_baru');
            $perluDikirim = $baseQuery('packing')->paginate($perPage, ['*'], 'page_dikirim');
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
     * Accept a paid order (move from paid to packing status)
     */
    public function acceptOrder(Request $request, Order $order): JsonResponse
    {
        try {
            $payment = $order->payment;
            
            if (!$payment || $payment->status !== 'settlement') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran belum lunas/diterima',
                ], 422);
            }

            if ($order->status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order status is not paid',
                ], 400);
            }

            $order->status = 'packing';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order accepted successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('Accept order error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept order',
            ], 500);
        }
    }

    /**
     * Handle process shipment for pengiriman section
     */
    public function processShipment(Request $request, Order $order): JsonResponse
    {
        try {
            if ($order->status !== 'packing') {
                return response()->json([
                    'success' => false,
                    'message' => 'Status pesanan harus packing sebelum bisa dikirim',
                ], 400);
            }

            $delivery = $this->shippingService->createShipment($order);

            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create shipment',
                ], 500);
            }

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
