<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{
    /**
     * Intercept and parse the live JSON payment callback webhook from Safaricom.
     */
    public function handleCallback(Request $request)
    {
        // Optional IP Validation (e.g. for production safety)
        if (config('services.mpesa.validate_ip', false)) {
            $allowedIps = array_map('trim', explode(',', config('services.mpesa.allowed_ips', '')));
            if (!in_array($request->ip(), $allowedIps)) {
                Log::warning('Unauthorized M-Pesa Callback IP Blocked', ['ip' => $request->ip()]);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Unauthorized IP Address'], 403);
            }
        }

        Log::info('Incoming M-Pesa Callback Received', ['payload' => $request->all()]);

        $body = $request->json()->all();
        
        if (!isset($body['Body']['stkCallback'])) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid structural template metadata']);
        }

        $callbackData = $body['Body']['stkCallback'];
        $resultCode = $callbackData['ResultCode'];
        $resultDesc = $callbackData['ResultDesc'];
        $checkoutRequestId = $callbackData['CheckoutRequestID'];

        // 1. Locate the pending payment tracking token record in database
        $payment = Payment::where('checkout_request_id', $checkoutRequestId)->first();

        if (!$payment) {
            Log::error('M-Pesa Callback Error: CheckoutRequestID not found inside local logs', ['checkout_request_id' => $checkoutRequestId]);
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Transaction tracking reference missing']);
        }

        // 2. Perform atomic calculation state modifications based on customer authorization success
        DB::transaction(function () use ($payment, $resultCode, $resultDesc, $callbackData) {
            if ($resultCode == 0) {
                // Payment Authorized Successfully by client
                $receiptNumber = null;
                $callbackItems = $callbackData['CallbackMetadata']['Item'] ?? [];

                foreach ($callbackItems as $item) {
                    if ($item['Name'] === 'MpesaReceiptNumber') {
                        $receiptNumber = $item['Value'];
                        break;
                    }
                }

                // Transition payment token states to safe completed boundaries
                $payment->update([
                    'status'               => 'completed',
                    'mpesa_receipt_number' => $receiptNumber,
                    'result_description'   => $resultDesc,
                ]);

                // Transition the parent order state automatically to approved status via OrderService
                $order = Order::with(['client', 'products'])->find($payment->order_id);
                if ($order) {
                    app(\App\Services\OrderService::class)->approve($order);
                }

                Log::info("M-Pesa Payment Captured Successfully against Order ID: {$payment->order_id}. Receipt: {$receiptNumber}");
            } else {
                // User cancelled, closed the prompt, or had insufficient funds
                $payment->update([
                    'status'             => 'failed',
                    'result_description' => $resultDesc,
                ]);

                Log::warning("M-Pesa Payment Failed or Cancelled for Order ID: {$payment->order_id}. Reason: {$resultDesc}");
            }
        });

        // Mandatory clean structural handshake message template required back by Safaricom
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Callback execution successfully logged inside ERP engine'
        ]);
    }
}