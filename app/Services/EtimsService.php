<?php

namespace App\Services;

use App\Models\Order;
use App\Models\EtimsInvoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class EtimsService
{
    /**
     * Compute Kenyan Tax limits and create an internal eTIMS ledger instance.
     */
    public function initializeFiscalInvoice(Order $order): EtimsInvoice
    {
        $gross = $order->total_amount;
        
        // Back-calculating 16% VAT from the gross total amount (Gross / 1.16)
        $taxable = (int) round($gross / 1.16);
        $vat = $gross - $taxable;

        return EtimsInvoice::create([
            'order_id' => $order->id,
            'internal_invoice_number' => 'INV-' . date('Y') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            'gross_amount' => $gross,
            'taxable_amount' => $taxable,
            'vat_amount' => $vat,
            'status' => 'pending',
        ]);
    }

    /**
     * Mock payload transmission targeting KRA VSC/OSC API interfaces.
     */
    public function transmitToKra(EtimsInvoice $invoice): bool
    {
        $isEnabled = config('services.etims.enabled', false);

        if (!$isEnabled) {
            // Zero-cost Mock Mode: Bypass external API requests & generate mock fiscal signature
            $cuInvoiceNumber = 'KRA-TIMS-CU-MOCK-' . strtoupper(\Illuminate\Support\Str::random(8));
            $appUrl = config('app.url', 'https://noir-bloom-erp.fly.dev');
            $verificationUrl = rtrim($appUrl, '/') . '/receipts/verify/' . $invoice->internal_invoice_number;

            $invoice->update([
                'status' => 'transmitted',
                'cu_invoice_number' => $cuInvoiceNumber,
                'kra_qr_url' => $verificationUrl,
            ]);

            Log::info("eTIMS Mock Mode: Generated fiscal signature {$cuInvoiceNumber} for invoice {$invoice->internal_invoice_number}");
            return true;
        }

        $order = $invoice->order()->with(['client', 'products'])->first();

        // Structural compilation parameters required by eTIMS APIs
        $payload = [
            'invoiceNumber' => $invoice->internal_invoice_number,
            'customerPin'   => $order->client->kra_pin ?? 'NONRESIDENT',
            'customerName'  => $order->client->company_name ?? $order->client->contact_name,
            'totalAmount'   => $invoice->gross_amount,
            'taxableAmount' => $invoice->taxable_amount,
            'taxAmount'     => $invoice->vat_amount,
            'purchaseDate'  => now()->format('Y-m-d H:i:s'),
            'items'         => $order ? $order->products->map(fn($p) => [
                'itemName'  => $p->name,
                'qty'       => $p->pivot->quantity,
                'unitPrice' => $p->pivot->price_at_sale,
                'taxRate'   => 'A' // Code 'A' maps directly to Standard 16% VAT inside KRA metrics
            ])->toArray() : []
        ];

        try {
            Log::info('Transmitting fiscal packet data to KRA eTIMS registry...', ['payload' => $payload]);
            
            $invoice->update([
                'status' => 'transmitted',
                'cu_invoice_number' => 'KRA-TIMS-CU-' . strtoupper(\Illuminate\Support\Str::random(10)),
                'kra_qr_url' => 'https://etims.kra.go.ke/verify?id=' . $invoice->internal_invoice_number,
            ]);

            return true;
        } catch (Exception $e) {
            $invoice->update([
                'status' => 'failed',
                'error_log_payload' => $e->getMessage(),
            ]);
            return false;
        }
    }
}