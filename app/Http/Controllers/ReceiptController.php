<?php

namespace App\Http\Controllers;

use App\Models\AccountsReceivableInvoice;
use App\Models\Order;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ReceiptController extends Controller
{
    /**
     * Handle printable tax receipt rendering & DOMPDF downloading for completed orders.
     */
    public function download(Order $order)
    {
        // 1. Load order relations
        $order->load(['client', 'products', 'etimsInvoice', 'payments', 'branch']);

        // 2. Assert payment is completed or B2B AR Invoice exists
        $completedPayment = $order->payments->first(fn($p) => $p->status === 'completed');
        $arInvoice = AccountsReceivableInvoice::where('order_id', $order->id)->first();

        if (!$completedPayment && !$arInvoice) {
            abort(403, 'Access Denied: Printable receipt is only available for completed transactions or corporate credit terms.');
        }

        // 3. Enforce authorization ownership or staff permissions (prevent IDOR)
        if (!request()->hasValidSignature()) {
            if (!auth()->check()) {
                abort(403, 'Access Denied: Secure signature is missing or expired.');
            }

            $user = auth()->user();
            $isOwner = $order->client && $order->client->user_id === $user->id;
            $isStaff = $user->account_tier?->isStaff() ?? false;

            if (!$isOwner && !$isStaff) {
                abort(403, 'Access Denied: You are not authorized to view this receipt.');
            }
        }

        $invoice = $order->etimsInvoice;
        $qrUrl = $invoice?->kra_qr_url ?? 'https://etims.kra.go.ke';

        // 4. Generate SVG QR Code
        $qrCodeSvg = $this->getQrCodeSvg($qrUrl);

        // 5. HTML preview override if format=html query param is provided
        if (request()->query('format') === 'html') {
            return view('receipt.download', compact('order', 'completedPayment', 'arInvoice', 'invoice', 'qrCodeSvg'));
        }

        // 6. Generate & Stream DOMPDF PDF document
        $filename = "Noir_Bloom_Receipt_ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ".pdf";

        $pdf = Pdf::loadView('pdf.receipt', compact('order', 'completedPayment', 'arInvoice', 'invoice', 'qrCodeSvg'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream($filename);
    }

    /**
     * Retrieve QR Code SVG vector string using BaconQrCode or localized vector fallback.
     */
    protected function getQrCodeSvg(string $url): string
    {
        try {
            $renderer = new ImageRenderer(
                new RendererStyle(100),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $svg = $writer->writeString($url);

            if ($svg && str_contains($svg, '<svg')) {
                return preg_replace('/<\?xml.*?\?>/i', '', $svg);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed generating QR code SVG: ' . $e->getMessage());
        }

        // Return vector fallback SVG
        return '
        <svg width="100" height="100" viewBox="0 0 100 100" class="mx-auto" xmlns="http://www.w3.org/2000/svg">
            <rect width="100" height="100" fill="none" stroke="#111" stroke-width="2"/>
            <rect x="6" y="6" width="24" height="24" fill="none" stroke="#111" stroke-width="4"/>
            <rect x="12" y="12" width="12" height="12" fill="#111"/>
            <rect x="70" y="6" width="24" height="24" fill="none" stroke="#111" stroke-width="4"/>
            <rect x="76" y="12" width="12" height="12" fill="#111"/>
            <rect x="6" y="70" width="24" height="24" fill="none" stroke="#111" stroke-width="4"/>
            <rect x="12" y="76" width="12" height="12" fill="#111"/>
            <rect x="36" y="36" width="28" height="28" fill="#111"/>
        </svg>
        ';
    }
}
