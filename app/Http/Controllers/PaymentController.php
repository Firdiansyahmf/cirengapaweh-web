<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        /* config midtrans */
        \Midtrans\Config::$serverKey = env("MIDTRANS_SERVER_KEY");
        \Midtrans\Config::$isProduction = env("MIDTRANS_IS_PRODUCTION", false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        /* disable verifikasi SSL saat di localhosts (HAPUS KALAU UDAH DEPLOT!!!) */
        if (!env("MIDTRANS_IS_PRODUCTION", false)) {
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [
                    'X-Midtrans-PHP-Patch: 1',
                ],
            ];
        }
    }

    public function processPayment(Request $request)
    {
        $productId = session("checkout_product_id");
        $productName = session("checkout_product");
        $price = session("checkout_price");
        $quantity = session("checkout_quantity");

        if (!$productId || !$productName || !$price || !$quantity) {
            return redirect("/")->with(
                "error",
                "Sesi checkout tidak ditemukan atau telah berakhir. Silahkan pilih produk kembali.",
            );
        }

        $validated = $request->validate([
            "customer_name" => "required|string|max:50",
            "customer_email" => "nullable|email|max:100",
            "whatsapp" => 'required|string|regex:/^[0-9]+$/|min:8|max:15',
            "shipping_address" => "required|string|max:200",
            /* "postal_code" => "required|string|max:5", */
            "payment_method" => "required|string|in:qris,bca,bni,bri",
        ]);

        $subtotal = $price * $quantity;
        $ongkir = 6000;
        $admin = 1000;
        $totalAmount = $subtotal + $ongkir + $admin;

        $invoiceNumber = "CA-" . strtoupper(Str::random(5)) . "-" . time();

        try {
            DB::beginTransaction();

            $order = Order::create([
                "user_id" => Auth::id(),
                "invoice_number" => $invoiceNumber,
                "customer_name" => $validated["customer_name"],
                "customer_email" =>
                    $validated["customer_email"] ?? "customer@example.com",
                "customer_phone" => $validated["whatsapp"],
                "shipping_address" => $validated["shipping_address"],
                /* "postal_code" => $validated["postal_code"], */
                "total_amount" => $totalAmount,
                "status" => "unpaid",
            ]);

            OrderItem::create([
                "order_id" => $order->id,
                "product_id" => $productId,
                "quantity" => $quantity,
                "unit_price" => $price,
            ]);

            $payment = Payment::create([
                "order_id" => $order->id,
                "transaction_id" => "",
                "payment_type" => $validated["payment_method"],
                "amount" => $totalAmount,
                "status" => "pending",
            ]);

            /* parameter untuk dikirim ke midtrans */
            $params = [
                "transaction_details" => [
                    "order_id" => $invoiceNumber,
                    "gross_amount" => (int) $totalAmount,
                ],
                "customer_details" => [
                    "first_name" => $validated["customer_name"],
                    "email" =>
                        $validated["customer_email"] ?? "customer@example.com",
                    "phone" => $validated["whatsapp"],
                ],
                "item_details" => [
                    [
                        "id" => "prod_" . $productId,
                        "price" => (int) $price,
                        "quantity" => (int) $quantity,
                        "name" => substr($productName, 0, 50),
                    ],
                    [
                        "id" => "shipping_fee",
                        "price" => $ongkir,
                        "quantity" => 1,
                        "name" => "Ongkos Kirim",
                    ],
                    [
                        "id" => "admin",
                        "price" => $admin,
                        "quantity" => 1,
                        "name" => "Biaya Admin",
                    ],
                ],
            ];

            $paymentMethod = $validated["payment_method"];

            if ($paymentMethod === "qris") {
                $params["payment_type"] = "qris";
                $params["qris"] = [
                    "acquirer" => "gopay",
                ];
            } elseif ($paymentMethod === "mandiri") {
                $params["payment_type"] = "echannel";
                $params["echannel"] = [
                    "bill_info1" => "Payment for order",
                    "bill_info2" => $invoiceNumber,
                ];
            } else {
                // BCA, BNI, BRI
                $params["payment_type"] = "bank_transfer";
                $params["bank_transfer"] = [
                    "bank" => $paymentMethod,
                ];
            }

            $response = \Midtrans\CoreApi::charge($params);

            $payment->transaction_id = $response->transaction_id;

            if ($paymentMethod === "qris") {
                $qrAction = collect($response->actions)->firstWhere(
                    "name",
                    "generate-qr-code",
                );
                $payment->qr_code_url = $qrAction ? $qrAction->url : null;
            } elseif ($paymentMethod === "mandiri") {
                $payment->va_number = $response->bill_key ?? null;
                $payment->biller_code = $response->biller_code ?? null;
                $payment->bank = "mandiri";
            } elseif ($paymentMethod === "permata") {
                $payment->va_number = $response->permata_va_number ?? null;
                $payment->bank = "permata";
            } else {
                // bca, bni, bri
                if (
                    isset($response->va_numbers) &&
                    count($response->va_numbers) > 0
                ) {
                    $payment->va_number = $response->va_numbers[0]->va_number;
                    $payment->bank = $response->va_numbers[0]->bank;
                }
            }

            if (isset($response->expiry_time)) {
                $payment->expiry_time = Carbon::parse($response->expiry_time);
            } else {
                $payment->expiry_time = now()->addHour();
            }

            $payment->save();

            DB::commit();

            session()->forget([
                "checkout_product_id",
                "checkout_product",
                "checkout_price",
                "checkout_quantity",
            ]);

            session([
                "midtrans_response" => $response,
                "active_invoice" => $order->invoice_number,
            ]);

            return redirect("/payment");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Payment processing failed: " . $e->getMessage(), [
                "exception" => $e,
                "request" => $request->all(),
            ]);
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "error",
                    "Terjadi kesalahan saat memproses transaksi: " .
                        $e->getMessage(),
                );
        }
    }

    public function showPayment($orderId)
    {
        $order = Order::with(["payment", "items.product"])->findOrFail(
            $orderId,
        );
        $payment = $order->payment;
        $orderItem = $order->items()->first();

        $timeRemaining = 0;
        if (
            $payment &&
            $payment->expiry_time &&
            $payment->status === "pending"
        ) {
            $timeRemaining = max(
                0,
                now()->diffInSeconds($payment->expiry_time, false),
            );
        }

        return view(
            "pages.payment",
            compact("order", "payment", "orderItem", "timeRemaining"),
        );
    }

    public function checkStatus($orderId)
    {
        $order = Order::with("payment")->findOrFail($orderId);

        if ($order->status === 'unpaid' && $order->payment && $order->payment->status === 'pending') {
            try {
                $statusResponse = \Midtrans\Transaction::status($order->invoice_number);

                if ($statusResponse) {
                    $transactionStatus = $statusResponse->transaction_status;
                    $fraudStatus = $statusResponse->fraud_status;

                    $newPaymentStatus = 'pending';
                    $newOrderStatus = 'unpaid';

                    if ($transactionStatus === 'capture') {
                        if ($fraudStatus === 'challenge') {
                            $newPaymentStatus = 'pending';
                            $newOrderStatus = 'unpaid';
                        } elseif ($fraudStatus === 'accept') {
                            $newPaymentStatus = 'settlement';
                            $newOrderStatus = 'paid';
                        }
                    } elseif ($transactionStatus === 'settlement') {
                        $newPaymentStatus = 'settlement';
                        $newOrderStatus = 'paid';
                    } elseif ($transactionStatus === 'deny') {
                        $newPaymentStatus = 'deny';
                        $newOrderStatus = 'cancelled';
                    } elseif ($transactionStatus === 'expire') {
                        $newPaymentStatus = 'expire';
                        $newOrderStatus = 'cancelled';
                    } elseif ($transactionStatus === 'cancel') {
                        $newPaymentStatus = 'cancel';
                        $newOrderStatus = 'cancelled';
                    }

                    if ($newPaymentStatus !== $order->payment->status) {
                        DB::transaction(function () use ($order, $newPaymentStatus, $newOrderStatus, $statusResponse) {
                            $order->payment->update([
                                'status' => $newPaymentStatus,
                                'transaction_id' => $statusResponse->transaction_id ?? $order->payment->transaction_id
                            ]);
                            $order->update([
                                'status' => $newOrderStatus,
                            ]);
                        });
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Could not sync payment status with Midtrans API: " . $e->getMessage());
            }
        }

        $order->load('payment');

        return response()->json([
            "status" => $order->status,
            "payment_status" => $order->payment
                ? $order->payment->status
                : "none",
        ]);
    }

    public function handleWebhook(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();

            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            Log::info(
                "Midtrans Webhook Received. Invoice: {$orderId}, Status: {$transactionStatus}",
            );

            $order = Order::where("invoice_number", $orderId)->first();
            if (!$order) {
                return response()->json(["message" => "Order not found"], 404);
            }

            $payment = $order->payment;
            if (!$payment) {
                return response()->json(
                    ["message" => "Payment record not found"],
                    404,
                );
            }

            $newPaymentStatus = "pending";
            $newOrderStatus = "unpaid";

            if ($transactionStatus === "capture") {
                if ($fraudStatus === "challenge") {
                    $newPaymentStatus = "pending";
                    $newOrderStatus = "unpaid";
                } elseif ($fraudStatus === "accept") {
                    $newPaymentStatus = "settlement";
                    $newOrderStatus = "paid";
                }
            } elseif ($transactionStatus === "settlement") {
                $newPaymentStatus = "settlement";
                $newOrderStatus = "paid";
            } elseif ($transactionStatus === "pending") {
                $newPaymentStatus = "pending";
                $newOrderStatus = "unpaid";
            } elseif ($transactionStatus === "deny") {
                $newPaymentStatus = "deny";
                $newOrderStatus = "cancelled";
            } elseif ($transactionStatus === "expire") {
                $newPaymentStatus = "expire";
                $newOrderStatus = "cancelled";
            } elseif ($transactionStatus === "cancel") {
                $newPaymentStatus = "cancel";
                $newOrderStatus = "cancelled";
            }

            DB::transaction(function () use (
                $order,
                $payment,
                $newPaymentStatus,
                $newOrderStatus,
                $notification,
            ) {
                $payment->update([
                    "status" => $newPaymentStatus,
                    "transaction_id" =>
                        $notification->transaction_id ??
                        $payment->transaction_id,
                ]);

                $order->update([
                    "status" => $newOrderStatus,
                ]);
            });

            return response()->json([
                "message" => "Notification processed successfully",
            ]);
        } catch (\Exception $e) {
            Log::error("Midtrans Webhook Error: " . $e->getMessage(), [
                "exception" => $e,
            ]);
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function getPaymentData()
    {
        $invoiceNumber = session("active_invoice");
        if (!$invoiceNumber) {
            header("Location: " . url("/"));
            exit;
        }

        $order = Order::with(["payment", "items.product"])->where("invoice_number", $invoiceNumber)->firstOrFail();

        if (request()->has("check_status")) {
            $statusResponse = $this->checkStatus($order->id);
            $statusResponse->send();
            exit;
        }

        $payment = $order->payment;
        $orderItem = $order->items()->first();
        $timeRemaining = 0;
        if ($payment && $payment->expiry_time && $payment->status === "pending") {
            $timeRemaining = max(0, now()->diffInSeconds($payment->expiry_time, false));
        }

        return compact("order", "payment", "orderItem", "timeRemaining");
    }
}
