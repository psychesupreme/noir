@php
    if (!function_exists('formatInvoicePhone')) {
        function formatInvoicePhone($phone) {
            if (empty($phone)) return 'Not Provided';
            $digits = preg_replace('/\D/', '', $phone);
            if (str_starts_with($digits, '254') && strlen($digits) === 12) {
                return '+254 ' . substr($digits, 3);
            }
            if (str_starts_with($digits, '0') && strlen($digits) === 10) {
                return '+254 ' . substr($digits, 1);
            }
            if (strlen($digits) === 9) {
                return '+254 ' . $digits;
            }
            return '+' . $digits;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proforma Invoice - Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} - Noir & Bloom</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* CSS variables for luxury theme */
        :root {
            --onyx-dark: #0b0b0d;
            --onyx-bg: #121215;
            --rose-gold: #c5a880;
            --champagne: #f4ebd9;
            --glass-border: rgba(197, 168, 128, 0.15);
            --paper-bg: #ffffff;
            --text-dark: #111111;
            --text-muted: #555555;
            --font-serif: 'Cinzel', Georgia, serif;
            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        /* Base styles for document viewer */
        body {
            margin: 0;
            padding: 0;
            background-color: var(--onyx-bg);
            color: var(--champagne);
            font-family: var(--font-sans);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        /* Top navigation and action bar */
        .action-bar {
            width: 100%;
            max-width: 800px;
            margin: 24px 0 16px 0;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(20, 20, 25, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            box-sizing: border-box;
            z-index: 10;
        }

        .action-title {
            font-family: var(--font-serif);
            font-size: 14px;
            letter-spacing: 2px;
            color: var(--rose-gold);
            margin: 0;
            font-weight: 600;
        }

        .btn-group {
            display: flex;
            gap: 12px;
        }

        .btn {
            font-family: var(--font-sans);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 10px 18px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background-color: var(--rose-gold);
            color: var(--onyx-dark);
            border: 1px solid var(--rose-gold);
        }

        .btn-primary:hover {
            background-color: transparent;
            color: var(--rose-gold);
            box-shadow: 0 0 12px rgba(197, 168, 128, 0.3);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--champagne);
            border: 1px solid rgba(244, 235, 217, 0.3);
        }

        .btn-secondary:hover {
            border-color: var(--champagne);
            background: rgba(255, 255, 255, 0.05);
        }

        /* Printable Receipt Container */
        .receipt-container {
            width: 100%;
            max-width: 800px;
            padding: 0 16px 40px 16px;
            box-sizing: border-box;
        }

        /* Receipt Card Styling */
        .receipt-card {
            background-color: var(--paper-bg);
            color: var(--text-dark);
            padding: 50px 60px;
            border-radius: 4px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }

        /* Header design styling */
        .receipt-header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px double #111;
            padding-bottom: 30px;
        }

        .atelier-logo {
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 6px;
            margin: 0 0 8px 0;
            text-transform: uppercase;
        }

        .receipt-subtitle {
            font-family: var(--font-sans);
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin: 0;
            font-weight: 500;
        }

        /* Info grids layout */
        .receipt-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
            font-size: 12px;
            line-height: 1.6;
        }

        .meta-section h3 {
            font-family: var(--font-serif);
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
            margin: 0 0 12px 0;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .meta-label {
            color: var(--text-muted);
        }

        .meta-value {
            font-weight: 500;
        }

        .meta-value.mono {
            font-family: var(--font-mono);
            font-size: 11px;
        }

        /* Product items data table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            font-size: 12px;
        }

        .items-table th {
            font-family: var(--font-serif);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-align: left;
            padding: 12px 8px;
            border-bottom: 1.5px solid #111;
            border-top: 1.5px solid #111;
        }

        .items-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .item-name {
            font-weight: 500;
        }

        .item-sku {
            font-family: var(--font-mono);
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 2px;
            display: block;
        }

        .mono-num {
            font-family: var(--font-mono);
            font-size: 11px;
        }

        /* Financial summary breakdown */
        .summary-wrapper {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            margin-bottom: 45px;
        }

        .etims-verification-block {
            border: 1px dashed #bbb;
            padding: 20px;
            border-radius: 4px;
            background: #fafafa;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .qr-wrapper {
            flex-shrink: 0;
            width: 100px;
            height: 100px;
            background: #fff;
            padding: 4px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-wrapper svg {
            width: 100%;
            height: 100%;
        }

        .etims-details {
            font-size: 11px;
            line-height: 1.5;
        }

        .etims-title {
            font-family: var(--font-serif);
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 11px;
            margin: 0 0 6px 0;
            color: #111;
        }

        .etims-code {
            font-family: var(--font-mono);
            background: #eaeaea;
            padding: 2px 4px;
            border-radius: 2px;
            word-break: break-all;
        }

        .totals-block {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            font-size: 12px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .totals-row.grand-total {
            border-bottom: 2px double #111;
            border-top: 1.5px solid #111;
            font-size: 15px;
            font-weight: 700;
            padding: 12px 0;
            font-family: var(--font-serif);
        }

        /* Footer policy declaration */
        .receipt-footer {
            text-align: center;
            font-size: 10px;
            color: var(--text-muted);
            border-top: 1px solid #eee;
            padding-top: 30px;
            line-height: 1.6;
        }

        .footer-branding {
            font-family: var(--font-serif);
            letter-spacing: 2px;
            font-size: 11px;
            margin-bottom: 6px;
            text-transform: uppercase;
            color: #111;
        }

        /* Print Media Query Optimization */
        @media print {
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                display: block;
                min-height: 0;
            }

            .action-bar {
                display: none !important;
            }

            .receipt-container {
                padding: 0 !important;
                max-width: 100% !important;
            }

            .receipt-card {
                box-shadow: none !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }

            /* Enable direct ink-friendly colors */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .etims-verification-block {
                background: #ffffff !important;
                border: 1px solid #111 !important;
            }

            .etims-code {
                background: transparent !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Browser Action Bar (hidden on print) -->
    <div class="action-bar">
        <div>
            <h1 class="action-title">Noir & Bloom Compliance Gateway</h1>
        </div>
        <div class="btn-group">
            <button onclick="window.print()" class="btn btn-primary">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-top: -2px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print Invoice
            </button>
            <a href="{{ route('storefront') }}" class="btn btn-secondary">
                Return to Store
            </a>
        </div>
    </div>

    <!-- Printable Receipt Wrapper -->
    <div class="receipt-container">
        <div class="receipt-card">
            
            <!-- Atelier Header -->
            <div class="receipt-header">
                <div class="atelier-logo">Noir & Bloom</div>
                <div class="receipt-subtitle">PROFORMA INVOICE / PURCHASE RECEIPT</div>
            </div>

            <!-- Meta Information Grid -->
            <div class="receipt-meta">
                <!-- Transaction Details -->
                <div class="meta-section">
                    <h3>Invoice Meta</h3>
                    <div class="meta-row">
                        <span class="meta-label">Invoice Number:</span>
                        <span class="meta-value mono">{{ $invoice?->internal_invoice_number ?? 'INV-' . date('Y') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Order Reference:</span>
                        <span class="meta-value mono">#NB-ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Transaction Date:</span>
                        <span class="meta-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Payment Status:</span>
                        <span class="meta-value" style="color: #2e7d32; font-weight: 600; text-transform: uppercase;">{{ $completedPayment ? $completedPayment->status : 'APPROVED (CREDIT)' }}</span>
                    </div>
                </div>

                <!-- Client Details -->
                <div class="meta-section">
                    <h3>Client Information</h3>
                    <div class="meta-row">
                        <span class="meta-label">Customer Name:</span>
                        <span class="meta-value">{{ $order->client->company_name ?: $order->client->contact_name }}</span>
                    </div>
                    @if($order->client->kra_pin)
                    <div class="meta-row">
                        <span class="meta-label">KRA PIN:</span>
                        <span class="meta-value mono">{{ $order->client->kra_pin }}</span>
                    </div>
                    @endif
                    <div class="meta-row">
                        <span class="meta-label">Contact Phone:</span>
                        <span class="meta-value mono">{{ formatInvoicePhone($order->client->phone) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Email Address:</span>
                        <span class="meta-value">{{ $order->client->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Items Table Grid -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 55%;">Product Description</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-right" style="width: 15%;">Unit Price</th>
                        <th class="text-right" style="width: 20%;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $product)
                    <tr>
                        <td>
                            <span class="item-name">{{ $product->name }}</span>
                            <span class="item-sku">SKU: {{ $product->sku ?: 'NB-' . str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="text-center mono-num">{{ $product->pivot->quantity }}</td>
                        <td class="text-right mono-num">KES {{ number_format($product->pivot->price_at_sale) }}</td>
                        <td class="text-right mono-num">KES {{ number_format($product->pivot->quantity * $product->pivot->price_at_sale) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($order->special_instructions)
            <div style="font-size: 11px; margin-bottom: 25px; border: 1px dashed var(--rose-gold); padding: 15px; border-radius: 4px; background: rgba(197, 168, 128, 0.03); text-align: left;">
                <h4 style="font-family: var(--font-serif); margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 1px; font-size: 11px; color: var(--rose-gold); font-weight: 600;">Curation Specifications (Hand Curator copy)</h4>
                <p style="margin: 0; line-height: 1.5; color: var(--text-dark); font-style: italic;">
                    {{ $order->special_instructions }}
                </p>
            </div>
            @endif

            <!-- Bottom Invoice Summary Wrapper -->
            <div class="summary-wrapper" style="display: flex; justify-content: flex-end;">
                
                <!-- Financial Totals -->
                <div class="totals-block" style="width: 100%; max-width: 380px;">
                    <div class="totals-row">
                        <span class="meta-label">Subtotal:</span>
                        <span class="mono-num">KES {{ number_format($order->total_amount - $order->service_fee_amount) }}</span>
                    </div>
                    @if($order->service_fee_amount > 0)
                    <div class="totals-row">
                        <span class="meta-label">Service/Delivery Fee:</span>
                        <span class="mono-num">KES {{ number_format($order->service_fee_amount) }}</span>
                    </div>
                    @endif
                    <div class="totals-row">
                        <span class="meta-label">Taxable Base (Net of VAT):</span>
                        <span class="mono-num">KES {{ number_format($invoice?->taxable_amount ?? (int)round($order->total_amount / 1.16)) }}</span>
                    </div>
                    <div class="totals-row">
                        <span class="meta-label">VAT (16% Standard Rate):</span>
                        <span class="mono-num">KES {{ number_format($invoice?->vat_amount ?? ($order->total_amount - (int)round($order->total_amount / 1.16))) }}</span>
                    </div>
                    <div class="totals-row grand-total">
                        <span>{{ $completedPayment ? 'Total Paid:' : 'Total Invoice Amount:' }}</span>
                        <span class="mono-num">KES {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>

            </div>

            <!-- Payment Logs Metadata -->
            @if($completedPayment)
            <div style="font-size: 11px; margin-bottom: 40px; border-top: 1px solid #eee; padding-top: 16px;">
                <h4 style="font-family: var(--font-serif); margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px; font-size: 11px;">M-Pesa Payment Details</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                    <div><span class="meta-label">Receipt Number:</span> <strong class="mono-num" style="display:block;">{{ $completedPayment->mpesa_receipt_number }}</strong></div>
                    <div><span class="meta-label">Payer Mobile:</span> <span class="mono-num" style="display:block;">{{ formatInvoicePhone($completedPayment->phone_number) }}</span></div>
                    <div><span class="meta-label">Payment ID:</span> <span class="mono-num" style="display:block;">{{ $completedPayment->merchant_request_id ?: 'STK-' . $completedPayment->id }}</span></div>
                </div>
            </div>
            @elseif($arInvoice)
            <div style="font-size: 11px; margin-bottom: 40px; border-top: 1px solid #eee; padding-top: 16px;">
                <h4 style="font-family: var(--font-serif); margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 1px; font-size: 11px;">B2B Credit Invoice Details</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px;">
                    <div><span class="meta-label">Payment Terms:</span> <strong style="display:block; text-transform: uppercase;">Net 30 Days</strong></div>
                    <div><span class="meta-label">Invoice Due Date:</span> <strong class="mono-num" style="display:block;">{{ $arInvoice->due_at->format('d M Y') }}</strong></div>
                    <div><span class="meta-label">Invoice Status:</span> <strong class="mono-num" style="display:block; text-transform: uppercase; color: {{ $arInvoice->status === 'paid' ? '#2e7d32' : '#c62828' }}">{{ $arInvoice->status }}</strong></div>
                    <div><span class="meta-label">Outstanding Balance:</span> <strong class="mono-num" style="display:block;">KES {{ number_format($arInvoice->balance_due) }}</strong></div>
                </div>
            </div>
            @endif

            <!-- Footer Policies -->
            <div class="receipt-footer">
                <div class="footer-branding">Noir & Bloom Atelier</div>
                <div>Luxury Floral Arrangements & Premium Bespoke Gifts</div>
                <div>Nairobi, Kenya • info@noirandbloom.co.ke • www.noirandbloom.co.ke</div>
                <div style="margin-top: 12px; font-size: 9px; font-style: italic;">
                    Thank you for choosing Noir & Bloom. All sales of fresh botanical arrangements are final.
                </div>
            </div>

        </div>
    </div>

    <!-- Trigger Printing Window on Page Load -->
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('noprint')) {
                // Short timeout to guarantee page CSS styling has fully loaded in rendering buffer
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html>
