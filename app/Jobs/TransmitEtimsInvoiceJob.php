<?php

namespace App\Jobs;

use App\Models\EtimsInvoice;
use App\Services\EtimsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TransmitEtimsInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public EtimsInvoice $invoice
    ) {}

    /**
     * Execute the job.
     */
    public function handle(EtimsService $etims): void
    {
        try {
            $success = $etims->transmitToKra($this->invoice);
            if ($success) {
                Log::info("eTIMS transmission job completed successfully for Invoice ID: {$this->invoice->id}");
            } else {
                Log::warning("eTIMS transmission job completed with transmission failure for Invoice ID: {$this->invoice->id}");
            }
        } catch (\Exception $e) {
            Log::error("eTIMS transmission job failed with exception for Invoice ID: {$this->invoice->id}", [
                'error' => $e->getMessage(),
            ]);
            $this->invoice->update([
                'status' => 'failed',
                'error_log_payload' => $e->getMessage(),
            ]);
        }
    }
}
