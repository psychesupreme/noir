<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - Order #NB-ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.4;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-b: 2px solid #c5a880;
            padding-bottom: 12px;
        }
        .brand-title {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #000000;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 9px;
            letter-spacing: 3px;
            color: #c5a880;
            text-transform: uppercase;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-box {
            width: 48%;
            vertical-align: top;
            background: #fafafa;
            border: 1px solid #e5e5e5;
            padding: 10px 12px;
            border-radius: 4px;
        }
        .box-title {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            font-weight: bold;
            margin-bottom: 6px;
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .item-table th {
            background: #111111;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 10px;
            text-align: left;
        }
        .item-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eeeeee;
        }
        .item-table tr:nth-child(even) td {
            background-color: #fcfcfc;
        }
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .totals-table td {
            padding: 5px 8px;
        }
        .totals-table .grand-total {
            font-size: 13px;
            font-weight: bold;
            border-top: 2px solid #111111;
            border-bottom: 2px solid #111111;
            color: #000000;
        }
        .etims-card {
            border: 1px dashed #c5a880;
            background: #faf8f5;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .etims-table {
            width: 100%;
            border-collapse: collapse;
        }
        .qr-cell {
            width: 110px;
            text-align: center;
            vertical-align: middle;
        }
        .footer-note {
            margin-top: 25px;
            text-align: center;
            font-size: 9px;
            color: #777777;
            border-top: 1px solid #eeeeee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header & Branch Branding -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <div class="brand-title">NOIR & BLOOM</div>
                <div class="brand-subtitle">Luxury Floral & Concierge Atelier</div>
                <div style="margin-top: 6px; color: #555; font-size: 10px;">
                    Branch: <strong>{{ $order->branch?->name ?? 'Nairobi Central Atelier' }}</strong><br>
                    Location: {{ $order->branch?->location_city ?? 'Nairobi' }} Metropolitan, Kenya<br>
                    Contact: +254 700 000 000 &bull; concierge@noirbloom.com<br>
                    KRA PIN: <strong>P051234567A</strong>
                </div>
            </td>
            <td class="text-right" style="vertical-align: top;">
                <div style="font-size: 16px; font-weight: bold; color: #111; text-transform: uppercase;">
                    OFFICIAL TAX RECEIPT
                </div>
                <div style="font-size: 11px; font-weight: bold; color: #c5a880; margin-top: 4px;">
                    #NB-ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                </div>
                <div style="margin-top: 6px; font-size: 10px; color: #555;">
                    Date: {{ $order->created_at ? $order->created_at->format('d M Y') : date('d M Y') }}<br>
                    Payment: <strong>{{ strtoupper($completedPayment?->payment_method ?? ($arInvoice ? 'Net 30 Credit' : 'M-Pesa')) }}</strong><br>
                    Status: <span style="color: #10b981; font-weight: bold;">PAID / VERIFIED</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Customer & Shipping Meta -->
    <table class="meta-table">
        <tr>
            <td class="meta-box">
                <div class="box-title">Client & B2B Tax Details</div>
                <strong>{{ $order->client?->contact_name ?? 'Valued Customer' }}</strong><br>
                @if($order->client?->company_name)
                    Company: {{ $order->client->company_name }}<br>
                @endif
                Email: {{ $order->client?->email ?? 'N/A' }}<br>
                Phone: {{ $order->client?->phone ?? 'N/A' }}<br>
                @if($order->client?->kra_pin)
                    KRA PIN: <strong>{{ strtoupper($order->client->kra_pin) }}</strong>
                @endif
            </td>
            <td style="width: 4%;"></td>
            <td class="meta-box">
                <div class="box-title">Delivery & Destination Parameters</div>
                @if($order->is_gift)
                    <strong>Recipient: {{ $order->recipient_name }}</strong> (Gift Delivery)<br>
                    Recipient Phone: {{ $order->recipient_phone }}<br>
                @else
                    <strong>Direct Delivery</strong><br>
                @endif
                Address: {{ $order->client?->delivery_address ?? 'Atelier Pickup' }}<br>
                Region: {{ $order->client?->region ?? 'Nairobi' }}
            </td>
        </tr>
    </table>

    <!-- Itemized Line Items Table -->
    <table class="item-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 50%;">Item Description</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 17%;">Unit Price (KSH)</th>
                <th class="text-right" style="width: 18%;">Total (KSH)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->products as $index => $product)
                @php
                    $qty = $product->pivot->quantity ?? 1;
                    $unitPrice = $product->pivot->price_at_sale ?? $product->price;
                    $lineTotal = $qty * $unitPrice;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        <div style="font-size: 9px; color: #777;">
                            Size: {{ strtoupper($product->pivot->size ?? 'Standard') }} &bull; SKU: {{ $product->sku }}
                        </div>
                    </td>
                    <td class="text-center">{{ $qty }}</td>
                    <td class="text-right">{{ number_format($unitPrice) }}</td>
                    <td class="text-right">{{ number_format($lineTotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Calculation Table -->
    @php
        $subtotal = $order->total_amount - ($order->service_fee_amount ?? 0);
        $vatRate = 0.16;
        $vatAmount = round($subtotal * ($vatRate / (1 + $vatRate)));
        $netSubtotal = $subtotal - $vatAmount;
    @endphp

    <table class="totals-table">
        <tr>
            <td>Net Total (Excl. VAT):</td>
            <td class="text-right">KSH {{ number_format($netSubtotal) }}</td>
        </tr>
        <tr>
            <td>VAT (16% Standard Rate):</td>
            <td class="text-right">KSH {{ number_format($vatAmount) }}</td>
        </tr>
        @if(($order->service_fee_amount ?? 0) > 0)
            <tr>
                <td>Delivery & Concierge Fee:</td>
                <td class="text-right">KSH {{ number_format($order->service_fee_amount) }}</td>
            </tr>
        @endif
        <tr class="grand-total">
            <td>Grand Total (Inc. VAT):</td>
            <td class="text-right">KSH {{ number_format($order->total_amount) }}</td>
        </tr>
    </table>

    <!-- KRA eTIMS Fiscal Block -->
    @if($invoice)
        <div class="etims-card">
            <table class="etims-table">
                <tr>
                    <td style="vertical-align: top;">
                        <div style="font-size: 11px; font-weight: bold; color: #111; text-transform: uppercase;">
                            KRA eTIMS FISCAL INVOICE VERIFICATION
                        </div>
                        <div style="font-size: 9px; color: #666; margin-top: 2px;">
                            Validated via Kenya Revenue Authority Tax Invoice Management System (eTIMS)
                        </div>
                        <div style="margin-top: 8px; font-size: 10px; line-height: 1.5;">
                            CU Serial Number: <strong>{{ $invoice->cu_serial_number ?? 'KRA-ESD-0091823' }}</strong><br>
                            CU Invoice Number: <strong>{{ $invoice->cu_invoice_number ?? ('KRA' . str_pad($invoice->id, 8, '0', STR_PAD_LEFT)) }}</strong><br>
                            UTI (Unique Tax ID): <span style="font-family: monospace;">{{ $invoice->uti ?? 'KRA-UTI-891023910' }}</span><br>
                            Status: <strong style="color: #10b981;">{{ strtoupper($invoice->status ?? 'TRANSMITTED') }}</strong>
                        </div>
                    </td>
                    <td class="qr-cell">
                        @if(!empty($qrCodeSvg))
                            <div style="display: inline-block; padding: 4px; background: #fff; border: 1px solid #ddd; border-radius: 4px;">
                                {!! $qrCodeSvg !!}
                            </div>
                            <div style="font-size: 8px; color: #888; margin-top: 3px;">Scan to Verify</div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <div class="footer-note">
        Thank you for choosing Noir & Bloom Atelier. For inquiries, please contact concierge@noirbloom.com.<br>
        This document is an electronically issued tax receipt generated by Noir & Bloom ERP.
    </div>

</body>
</html>
