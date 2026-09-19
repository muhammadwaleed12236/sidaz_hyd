<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty Sale Invoice - {{ $invoice->invoice_no }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .no-print-bar {
            background: #1e293b;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }

        .no-print-bar h3 {
            margin: 0;
            font-size: 16px;
        }

        .btn-print {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        .btn-back {
            background: #64748b;
            color: #fff;
            border: none;
            padding: 8px 14px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin-right: 8px;
        }

        .invoice-box {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            background: #fff;
            padding: 15px;
            position: relative;
        }

        /* Top Header */
        .header-section {
            text-align: center;
            margin-bottom: 12px;
        }

        .company-title {
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 0.8px;
            margin: 0;
            text-transform: uppercase;
        }

        .me-no {
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 1px;
        }

        .company-address {
            font-size: 11px;
            font-weight: 500;
            margin-top: 3px;
        }

        .invoice-heading {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 6px;
            text-decoration: underline;
        }

        /* Info Grid Table */
        .info-grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border: 1px solid #000;
        }

        .info-grid-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 11px;
            border: 1px solid #000;
        }

        .info-grid-table .label {
            font-weight: bold;
            display: inline-block;
            min-width: 110px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #000;
        }

        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 11px;
        }

        .items-table th {
            font-weight: bold;
            text-align: center;
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 10.5px;
        }

        .items-table td.center { text-align: center; }
        .items-table td.right { text-align: right; }
        .items-table td.bold { font-weight: bold; }

        .total-row td {
            font-weight: bold;
            font-size: 11.5px;
            border-top: 2px solid #000;
        }

        /* Warranty Section */
        .warranty-section {
            margin-top: 15px;
            font-size: 10.5px;
            line-height: 1.4;
        }

        .warranty-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 4px;
        }

        .warranty-body {
            text-align: justify;
        }

        /* Signatures & Stamp */
        .footer-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            position: relative;
        }

        .sign-block {
            font-size: 11px;
            line-height: 1.4;
        }

        .sign-block .name {
            font-weight: bold;
            text-transform: uppercase;
        }

        .sign-line {
            margin-top: 25px;
            display: flex;
            align-items: baseline;
            gap: 10px;
        }

        .sign-line-text {
            font-weight: bold;
        }

        .signature-hand {
            font-family: 'Brush Script MT', cursive, sans-serif;
            font-size: 24px;
            font-weight: bold;
            font-style: italic;
            color: #0d233a;
            border-bottom: 1px solid #000;
            padding: 0 25px 2px 10px;
            display: inline-block;
        }

        /* Round Seal Stamp Graphic representation */
        .official-seal {
            width: 100px;
            height: 100px;
            border: 3px double #0d3b66;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #0d3b66;
            opacity: 0.85;
            font-family: Arial, sans-serif;
            transform: rotate(-12deg);
            position: absolute;
            right: 40px;
            bottom: 0px;
        }

        .official-seal .seal-top {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .official-seal .seal-star {
            font-size: 10px;
            margin: 2px 0;
        }

        .official-seal .seal-bottom {
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }
            body {
                background: #fff;
                margin: 0;
            }
            .invoice-box {
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

<div class="no-print-bar">
    <h3><i class="fas fa-print me-2"></i>Print Preview - Warranty Sale Invoice #{{ $invoice->invoice_no }}</h3>
    <div>
        <a href="{{ route('dummy-invoices.index') }}" class="btn-back">← Back to List</a>
        <button onclick="window.print()" class="btn-print">🖨️ Print Invoice</button>
    </div>
</div>

<div class="invoice-box">
    <!-- Header -->
    <div class="header-section">
        <h1 class="company-title">SIDAZ PHARMACEUTICAL</h1>
        <div class="me-no">(NUTRACEUTICAL) M.E. No. 01507</div>
        <div class="company-address">A-64/65 Sindh Small Industries Extension Hyderabad Sindh Pakistan.</div>
        <div class="invoice-heading">WARRANTY SALE INVOICE</div>
    </div>

    <!-- Metadata Table Grid -->
    <table class="info-grid-table">
        <tr>
            <td style="width: 65%;">
                <div style="margin-bottom: 4px;"><span class="label">Invoice #:</span> <strong>{{ $invoice->invoice_no }}</strong></div>
                <div style="margin-bottom: 4px;"><span class="label">Customer Name:</span> <strong>{{ $invoice->customer_name }}</strong></div>
                <div style="margin-bottom: 4px;"><span class="label">Address:</span> {{ $invoice->customer_address ?? '-' }}</div>
                <div><span class="label">Licence No:</span> {{ $invoice->licence_no ?? '-' }} @if($invoice->licence_expiry) (Expiry: {{ $invoice->licence_expiry }}) @endif</div>
            </td>
            <td style="width: 35%;">
                <div style="margin-bottom: 4px;"><span class="label">Invoice Date:</span> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('n/j/Y') }}</div>
                <div style="margin-bottom: 4px;"><span class="label">Order Date:</span> {{ $invoice->order_date ? \Carbon\Carbon::parse($invoice->order_date)->format('n/j/Y') : '-' }}</div>
                <div><span class="label">Gate Pass #:</span> {{ $invoice->gate_pass_no ?? '-' }}</div>
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45px;">S.No</th>
                <th>Product Name</th>
                <th style="width: 65px;">Pack</th>
                <th style="width: 90px;">Batch No</th>
                <th style="width: 70px;">Mfg. Date</th>
                <th style="width: 70px;">Exp. Date</th>
                <th style="width: 55px;">Qty.</th>
                <th style="width: 65px;">MRP</th>
                <th style="width: 65px;">T.P.</th>
                <th style="width: 65px;">D.P</th>
                <th style="width: 95px;">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="bold">{{ $item->product_name }}</td>
                    <td class="center">{{ $item->pack ?? '-' }}</td>
                    <td class="center bold">{{ $item->batch_no ?? '-' }}</td>
                    <td class="center">{{ $item->mfg_date ?? '-' }}</td>
                    <td class="center">{{ $item->exp_date ?? '-' }}</td>
                    <td class="center bold">{{ number_format($item->qty, 0) }}</td>
                    <td class="right">{{ number_format($item->mrp, 2) }}</td>
                    <td class="right">{{ number_format($item->tp, 2) }}</td>
                    <td class="right bold">{{ number_format($item->dp, 2) }}</td>
                    <td class="right bold">{{ number_format($item->total_amount, 0) }}</td>
                </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="10" class="right">Total Amount</td>
                <td class="right">{{ number_format($invoice->total_amount, 0) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Warranty Section -->
    <div class="warranty-section">
        <div class="warranty-title">Warranty:</div>
        <div class="warranty-body">
            {{ $invoice->warranty_text }}
        </div>
    </div>

    <!-- Signatures & Stamp -->
    <div class="footer-section">
        <div class="sign-block">
            <div class="name">{{ $invoice->signatory_name ?? 'SEEMA KHAN' }}</div>
            <div>{{ $invoice->signatory_title ?? 'Production Incharge.' }}</div>
            <div><strong>SIDAZ PHARMACEUTICAL(Nutraceutical)</strong></div>
            <div class="sign-line">
                <span class="sign-line-text">Signature</span>
                <span class="signature-hand">Seema</span>
            </div>
        </div>

        <div class="official-seal">
            <div class="seal-top">SIDAZ</div>
            <div class="seal-star">★</div>
            <div class="seal-bottom">PHARMACEUTICAL</div>
        </div>
    </div>
</div>

</body>
</html>
