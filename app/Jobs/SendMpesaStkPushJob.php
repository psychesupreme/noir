<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Services\MpesaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMpesaStkPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $paymentId,
        public string $phone,
        public int $amount,
        public int $orderId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MpesaService $mpesa): void
    {
        $payment = Payment::find($this->paymentId);
        if (!$payment) {
            Log::error("Payment ID {$this->paymentId} not found during STK push dispatch.");
            return;
        }

        try {
            $order = \App\Models\Order::with('products')->find($this->orderId);
            $firstProduct = $order?->products?->first();
            $productName = $firstProduct ? $firstProduct->name : 'AtelierOrder';
            
            // Clean it: remove spaces and non-alphanumeric characters, max 12 chars
            $reference = preg_replace('/[^A-Za-z0-9]/', '', $productName);
            $reference = substr($reference, 0, 12);
            if (empty($reference)) {
                $reference = 'AtelierOrder';
            }

            $response = $mpesa->sendStkPush(
                phone: $this->phone,
                amount: $this->amount,
                reference: $reference
            );

            $payment->update([
                'merchant_request_id' => $response['MerchantRequestID'] ?? null,
                'checkout_request_id' => $response['CheckoutRequestID'] ?? null,
            ]);

            Log::info("M-Pesa STK Push Job completed successfully for Payment ID: {$this->paymentId}");
        } catch (\Exception $e) {
            Log::error("M-Pesa STK Push Job Failed for Payment ID: {$this->paymentId}", [
                'error' => $e->getMessage(),
            ]);
            $payment->update([
                'status' => 'failed',
                'result_description' => 'STK initiation failed: ' . $e->getMessage(),
            ]);
        }
    }
}
