<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Noir &amp; Bloom Receipt</title>
    <style>
        body {
            background-color: #09090B;
            color: #E4E4E7;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        .wrapper {
            width: 100%;
            background-color: #09090B;
            padding: 40px 0;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #121214;
            border: 1px solid #1F1F23;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }
        .header {
            padding: 45px 40px 30px 40px;
            text-align: center;
            border-bottom: 1px solid #1F1F23;
        }
        .logo-subtitle {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.4em;
            color: #D4AF37;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: block;
        }
        .logo-title {
            font-family: Georgia, serif;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.3em;
            color: #FFFFFF;
            text-transform: uppercase;
            margin: 0;
        }
        .content {
            padding: 40px;
        }
        .success-banner {
            text-align: center;
            margin-bottom: 35px;
        }
        .success-icon {
            display: inline-block;
            width: 48px;
            height: 48px;
            line-height: 48px;
            background-color: rgba(212, 175, 55, 0.1);
            color: #D4AF37;
            border-radius: 50%;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .title {
            font-family: Georgia, serif;
            color: #FFFFFF;
            font-size: 20px;
            font-style: italic;
            font-weight: normal;
            margin: 0 0 8px 0;
        }
        .subtitle {
            font-size: 11px;
            color: #71717A;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin: 0;
        }
        .invoice-details {
            margin-bottom: 30px;
            font-size: 12px;
            color: #A1A1AA;
            line-height: 1.6;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 4px 0;
            font-weight: 300;
        }
        .invoice-details .label {
            color: #71717A;
            width: 100px;
        }
        .invoice-details .val {
            text-align: right;
            color: #E4E4E7;
        }
        .table-title {
            font-family: Georgia, serif;
            font-size: 14px;
            color: #FFFFFF;
            margin: 0 0 15px 0;
            font-weight: normal;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 30px;
        }
        .items-table th {
            border-bottom: 1px solid #1F1F23;
            padding: 10px 0;
            text-align: left;
            color: #71717A;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
        }
        .items-table td {
            border-bottom: 1px solid #1F1F23;
            padding: 14px 0;
            color: #E4E4E7;
            font-weight: 300;
            vertical-align: top;
        }
        .items-table .price-col {
            text-align: right;
            color: #E4E4E7;
        }
        .items-table .size-tag {
            display: inline-block;
            font-size: 9px;
            background-color: #1F1F23;
            color: #A1A1AA;
            padding: 2px 6px;
            border-radius: 4px;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .totals-table {
            width: 100%;
            font-size: 13px;
            margin-bottom: 35px;
        }
        .totals-table td {
            padding: 6px 0;
            font-weight: 300;
        }
        .totals-table .label {
            color: #A1A1AA;
        }
        .totals-table .val {
            text-align: right;
            color: #E4E4E7;
        }
        .totals-table .grand-row td {
            border-top: 1px solid #1F1F23;
            padding-top: 15px;
            font-size: 16px;
            font-weight: 600;
        }
        .totals-table .grand-row .val {
            color: #D4AF37;
        }
        .etims-box {
            background-color: #161619;
            border: 1px solid #232329;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 35px;
            font-size: 11px;
            color: #A1A1AA;
            line-height: 1.6;
        }
        .etims-box strong {
            color: #FFFFFF;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #FFFFFF;
            color: #000000;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .footer {
            padding: 30px 40px;
            background-color: #0C0C0E;
            text-align: center;
            border-top: 1px solid #1F1F23;
            font-size: 10px;
            color: #52525B;
            letter-spacing: 0.05em;
        }
        .footer a {
            color: #A1A1AA;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <span class="logo-subtitle">Atelier</span>
                <h1 class="logo-title">Noir &amp; Bloom</h1>
            </div>
            <div class="content">
                <div class="success-banner">
                    <div class="success-icon">&checkmark;</div>
                    <h2 class="title">Payment Approved</h2>
                    <p class="subtitle">Order Confirmed</p>
                </div>

                <div class="invoice-details">
                    <table>
                        <tr>
                            <td class="label">Order Ref</td>
                            <td class="val">#NB-ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Date Paid</td>
                            <td class="val">{{ $order->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Client</td>
                            <td class="val">{{ $order->client?->contact_name ?: 'Retail Guest' }}</td>
                        </tr>
                    </table>
                </div>

                <h3 class="table-title">Your Selection</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th class="price-col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->products as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong><br>
                                    <span class="size-tag">{{ $product->pivot->size ?? 'standard' }}</span>
                                    <span style="color: #71717A; font-size: 11px;">&times; {{ $product->pivot->quantity }}</span>
                                </td>
                                <td class="price-col">
                                    KES {{ number_format($product->pivot->price_at_sale * $product->pivot->quantity) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="totals-table">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="val">KES {{ number_format($order->total_amount - $order->service_fee_amount) }}</td>
                    </tr>
                    @if($order->service_fee_amount > 0)
                        <tr>
                            <td class="label">Service &amp; Logistics Fee</td>
                            <td class="val">KES {{ number_format($order->service_fee_amount) }}</td>
                        </tr>
                    @endif
                    <tr class="grand-row">
                        <td class="label" style="color: #FFFFFF;">Total Paid</td>
                        <td class="val">KES {{ number_format($order->total_amount) }}</td>
                    </tr>
                </table>

                @if($order->etimsInvoice)
                    <div class="etims-box">
                        <strong>KRA eTIMS Tax Invoice Details</strong><br>
                        Invoice Number: {{ $order->etimsInvoice->kra_invoice_number }}<br>
                        Tax Signature: {{ $order->etimsInvoice->kra_tax_signature }}<br>
                        Fiscal Code: {{ $order->etimsInvoice->kra_internal_data }}
                    </div>
                @endif

                <div class="btn-container">
                    <a href="{{ route('receipt.download', ['order' => $order->id]) }}" class="btn">Download Tax Receipt</a>
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} Noir &amp; Bloom. All rights reserved.<br>
                This receipt confirms your transaction. Thank you for your patronage.<br>
                For concierge assistance, reach out to <a href="mailto:concierge@noirbloom.com">concierge@noirbloom.com</a>.
            </div>
        </div>
    </div>
</body>
</html>
