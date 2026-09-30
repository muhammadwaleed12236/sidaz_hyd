<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $sale->invoice_no ?: $sale->id }}</title>
    <!-- Use Bootstrap for grid and utilities -->
    <link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #1e293b;
            --accent-color: #2563eb;
            --border-color: #cbd5e1;
            --text-color: #0f172a;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            -webkit-print-color-adjust: exact;
        }

        .invoice-container {
            max-width: 210mm;
            margin: 15px auto;
            background: #ffffff;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            min-height: 297mm;
            position: relative;
            border-radius: 8px;
        }

        .company-header {
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary-color);
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .invoice-title-badge {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 6px;
            letter-spacing: 1px;
            display: inline-block;
        }

        .info-box {
            border: 1px solid #cbd5e1;
            padding: 10px 12px;
            border-radius: 6px;
            background-color: #ffffff;
            height: 100%;
        }

        .info-box-header {
            font-weight: 700;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 6px;
            padding-bottom: 4px;
            color: var(--primary-color);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            min-width: 75px;
            display: inline-block;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
        }

        .invoice-table th {
            background-color: #1e293b;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 700;
            padding: 8px 6px;
            letter-spacing: 0.5px;
            border: 1px solid #1e293b;
        }

        .invoice-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            vertical-align: middle;
            font-size: 12px;
        }

        .invoice-table tbody tr:nth-of-type(even) {
            background-color: #f8fafc;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .totals-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .totals-table .total-row td {
            border-top: 2px solid var(--primary-color);
            font-weight: 700;
            font-size: 14px;
            color: var(--primary-color);
        }

        .signature-area {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 180px;
            text-align: center;
            padding-top: 5px;
            font-weight: 600;
        }

        .print-btn-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
                font-size: 11pt !important;
            }

            .invoice-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                min-height: auto !important;
                border-radius: 0 !important;
                color: #000000 !important;
            }

            .print-btn-container, .no-print {
                display: none !important;
            }

            /* Black & White Paper Print Enhancements */
            .company-name {
                color: #000000 !important;
            }

            .invoice-title-badge {
                background-color: transparent !important;
                color: #000000 !important;
                border: 2px solid #000000 !important;
                padding: 4px 12px !important;
            }

            .info-box {
                border: 1px solid #000000 !important;
                background: transparent !important;
                color: #000000 !important;
                border-radius: 0 !important;
            }

            .info-box-header {
                color: #000000 !important;
                border-bottom: 1px solid #000000 !important;
            }

            .info-label {
                color: #000000 !important;
            }

            .badge {
                border: 1px solid #000000 !important;
                background-color: transparent !important;
                color: #000000 !important;
            }

            .text-primary, .text-secondary, .text-success, .text-danger, .text-warning, .text-muted, .text-dark {
                color: #000000 !important;
            }

            .invoice-table {
                border: 1.5px solid #000000 !important;
                border-radius: 0 !important;
            }

            .invoice-table th {
                background-color: #f1f5f9 !important;
                color: #000000 !important;
                border: 1px solid #000000 !important;
                -webkit-print-color-adjust: exact;
            }

            .invoice-table td {
                border: 1px solid #000000 !important;
                color: #000000 !important;
            }

            .invoice-table tbody tr:nth-of-type(even) {
                background-color: transparent !important;
            }

            .totals-table td {
                border-bottom: 1px solid #000000 !important;
                color: #000000 !important;
            }

            .totals-table .closing-bal td {
                border: 1.5px solid #000000 !important;
                background: transparent !important;
            }

            @page {
                size: A4 portrait;
                margin: 8mm 10mm 10mm 10mm;
            }
        }
    </style>
</head>

<body>

    <!-- Print Controls -->
    <div class="print-btn-container">
        <button onclick="window.print()" class="btn btn-primary btn-sm shadow d-inline-flex align-items-center gap-2 fw-bold">
            <i class="fas fa-print"></i> Print Invoice
        </button>
        <a href="{{ route('sale.index') }}" class="btn btn-secondary btn-sm shadow ms-2 fw-bold">Back</a>
    </div>

    <div class="invoice-container">
        <!-- Header Section -->
        @if(!($isEstimate ?? false))
        <div class="company-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <div class="company-name">{{ \App\Models\Setting::get('company_name', 'ProWave Technologies') }}</div>
                <div style="font-size: 12px; color: #475569;">{{ \App\Models\Setting::get('company_address', 'Hyderabad') }}</div>
                <div style="font-size: 11px; color: #64748b;"><i class="fas fa-phone-alt me-1"></i>{{ \App\Models\Setting::get('company_phone', '+92 317 3836223') }}</div>
            </div>
            <div class="text-end">
                <div class="invoice-title-badge">Sales Invoice</div>
                <div class="font-monospace fw-bold text-primary mt-1" style="font-size: 14px;">#{{ $sale->invoice_no }}</div>
            </div>
        </div>
        @else
        <div class="company-header d-flex justify-content-between align-items-center">
            <div class="company-name">{{ \App\Models\Setting::get('company_name', 'ProWave Technologies') }}</div>
            <div class="invoice-title-badge bg-secondary">Estimate</div>
        </div>
        @endif

        <!-- Customer & Reference Grid -->
        @if(!($isEstimate ?? false))
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="info-box">
                    <div class="info-box-header"><i class="fas fa-user me-1"></i> Customer Information</div>
                    @if($sale->customer_relation?->customer_id)
                    <div style="font-size: 11px; color: #64748b;">
                        Code: <strong>{{ $sale->customer_relation->customer_id }}</strong>
                    </div>
                    @endif
                    <div><span class="info-label">Name:</span> <strong>{{ $sale->walkin_name ?? ($sale->customer_relation->customer_name ?? 'Walk-in Customer') }}</strong></div>
                    <div><span class="info-label">Address:</span> <span>{{ $sale->customer_relation->address ?? '—' }}</span></div>
                    <div><span class="info-label">Mobile:</span> <span>{{ $sale->customer_relation->mobile ?? '—' }}</span></div>
                </div>
            </div>

            <div class="col-6">
                <div class="info-box">
                    <div class="info-box-header"><i class="fas fa-file-alt me-1"></i> Invoice Reference</div>
                    <div><span class="info-label">Invoice #:</span> <strong>{{ $sale->invoice_no }}</strong></div>
                    <div><span class="info-label">Date:</span> {{ $sale->created_at->format('d/m/Y') }}</div>
                    <div><span class="info-label">Status:</span> <span class="badge bg-success text-white px-2 py-1">{{ strtoupper($sale->sale_status ?? 'Posted') }}</span></div>
                    @if($sale->reference)
                    <div style="margin-top:4px; padding-top:4px; border-top:1px dashed #cbd5e1;">
                        <span class="info-label">Remarks:</span>
                        <span>{{ $sale->reference }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Remarks / Return Notes -->
        @if ($sale->return_note)
            <div class="row mb-2">
                <div class="col-12">
                    <div class="info-box" style="min-height: auto; padding: 6px 10px; background-color: #f8fafc; font-style: italic;">
                        <strong>Note:</strong> {{ $sale->return_note }}
                    </div>
                </div>
            </div>
        @endif

        @php
            $productionBatches = \App\Models\ProductionBatch::where('sale_id', $sale->id)->get();
            $producedByProduct = $productionBatches->groupBy('product_id')->map(fn($b) => $b->sum('quantity'));

            $totalBookedPcs = 0;
            $totalOutPcs = 0;
            foreach ($saleItems as $item) {
                $ordered = (float) ($item['total_pieces'] > 0 ? $item['total_pieces'] : ($item['qty'] * ($item['pieces_per_box'] ?? 1)));
                $totalBookedPcs += $ordered;
                $pId = $item['product_id'] ?? null;
                $totalOutPcs += (float) ($pId ? ($producedByProduct[$pId] ?? 0) : 0);
            }
            $totalRemPcs = max(0, $totalBookedPcs - $totalOutPcs);

            // ONLY show partial breakdown if there is actual remaining quantity AND some output produced
            $hasPartialDispatch = ($totalBookedPcs > 0 && $totalRemPcs > 0 && $totalOutPcs > 0);
        @endphp

        {{-- Partial Order Status Banner (Hidden for 100% completed orders) --}}
        @if ($hasPartialDispatch)
        <div class="row g-2 mb-3">
            <div class="col-12">
                <div class="info-box p-2" style="background-color: #fffbeb; border: 1px solid #fde68a;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <span class="fw-bold text-dark me-2"><i class="fas fa-hourglass-half text-warning me-1"></i> Order Batch & Partial Delivery Status:</span>
                            <span class="badge bg-warning text-dark border border-warning px-2 py-1">Partially Dispatched ({{ number_format($totalOutPcs) }} / {{ number_format($totalBookedPcs) }} Pcs)</span>
                        </div>
                        <div style="font-size: 11px;" class="font-monospace">
                            <span class="me-3"><strong>Booked Qty:</strong> <span class="text-primary fw-bold">{{ number_format($totalBookedPcs) }} Pcs</span></span>
                            <span class="me-3"><strong>Current Out:</strong> <span class="text-success fw-bold">{{ number_format($totalOutPcs) }} Pcs</span></span>
                            <span><strong>Remaining Bal:</strong> <span class="text-danger fw-bold">{{ number_format($totalRemPcs) }} Pcs</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    @if ($hasPartialDispatch)
                        <th class="text-start" style="width: 28%">Description</th>
                        <th class="text-center" style="width: 12%">Booked Qty</th>
                        <th class="text-center" style="width: 12%">Current Out</th>
                        <th class="text-center" style="width: 12%">Remaining</th>
                        <th class="text-end" style="width: 12%">Booking Rate</th>
                        <th class="text-end" style="width: 10%">Disc</th>
                        <th class="text-end" style="width: 14%">Net Amount</th>
                    @else
                        <th class="text-start" style="width: 38%">Description</th>
                        <th class="text-center" style="width: 14%">Shipped Qty</th>
                        <th class="text-center" style="width: 12%">UOM</th>
                        <th class="text-end" style="width: 12%">Unit Price</th>
                        <th class="text-end" style="width: 10%">Disc</th>
                        <th class="text-end" style="width: 14%">Net Amount</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($saleItems as $item)
                    @php
                        $height = $item['height'] ?? 0;
                        $width = $item['width'] ?? 0;

                        $piecesPerBox = (int)($item['pieces_per_box'] ?? 1);
                        if ($piecesPerBox <= 0) $piecesPerBox = 1;

                        $orderedPcs = (float) ($item['total_pieces'] > 0 ? $item['total_pieces'] : ($item['qty'] * $piecesPerBox));
                        $pId = $item['product_id'] ?? null;
                        $outPcs = (float) ($pId ? ($producedByProduct[$pId] ?? 0) : 0);
                        $remPcs = max(0, $orderedPcs - $outPcs);

                        $unitRate = (float) ($item['price'] > 0 ? $item['price'] : ($item['price_per_piece'] > 0 ? $item['price_per_piece'] : ($orderedPcs > 0 ? $item['total'] / $orderedPcs : 0)));
                        $discAmt  = (float) ($item['discount_amount'] ?? 0);
                        $discPct  = (float) ($item['discount_percent'] ?? 0);
                        $lineTotal = (float) ($item['total'] ?? 0);
                    @endphp
                    <tr>
                        <td class="text-start">
                            <div style="font-weight: bold; font-size: 12px; margin-bottom: 2px;">
                                {{ $item['item_name'] }}
                                @if (!empty($item['item_code']))
                                    <span class="text-muted fw-normal ms-1" style="font-size: 11px;">({{ $item['item_code'] }})</span>
                                @endif
                            </div>

                            <div style="font-size: 11px; color: #555; line-height: 1.2;">
                                @if (!empty($item['color']))
                                    <span class="badge bg-light text-dark border p-1" style="font-size: 9px; line-height:1;">
                                        @foreach ($item['color'] as $clr)
                                            {{ $clr }}
                                        @endforeach
                                    </span>
                                @endif

                                @if ($piecesPerBox > 1)
                                    <span class="d-inline-block ms-1">Pack: {{ $piecesPerBox }} pcs</span>
                                @endif
                            </div>
                        </td>

                        @if ($hasPartialDispatch)
                            <td class="text-center font-monospace fw-bold text-dark" style="vertical-align: middle;">
                                {{ number_format($orderedPcs) }} Pcs
                            </td>

                            <td class="text-center font-monospace fw-bold text-success" style="vertical-align: middle;">
                                {{ number_format($outPcs) }} Pcs
                            </td>

                            <td class="text-center font-monospace {{ $remPcs > 0 ? 'fw-bold text-danger' : 'text-muted' }}" style="vertical-align: middle;">
                                {{ number_format($remPcs) }} Pcs
                            </td>
                        @else
                            <td class="text-center font-monospace fw-bold text-dark" style="vertical-align: middle;">
                                {{ number_format($orderedPcs) }} Pcs
                            </td>
                            <td class="text-center font-monospace fw-bold" style="vertical-align: middle;">
                                Pieces
                            </td>
                        @endif

                        <td class="text-end font-monospace" style="vertical-align: middle;">
                            Rs. {{ number_format($unitRate, 2) }}
                        </td>

                        {{-- DISCOUNT COLUMN --}}
                        <td class="text-end font-monospace" style="vertical-align: middle;">
                            @if ($discAmt > 0)
                                <span class="text-danger">Rs. {{ number_format($discAmt, 2) }}</span>
                                @if ($discPct > 0)
                                    <br><small class="text-muted">({{ number_format($discPct, 1) }}%)</small>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="text-end font-monospace fw-bold text-dark" style="vertical-align: middle;">
                            Rs. {{ number_format($lineTotal, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $exchangeReturn = \App\Models\SaleReturn::with('items.product')->where('remarks', 'LIKE', '%Invoice #'.$sale->invoice_no.'%')->first();
            $exchangeReturnedAmount = 0;
            if ($exchangeReturn) {
                $exchangeReturnedAmount = $exchangeReturn->items->sum('line_total');
            }
        @endphp

        @if($exchangeReturn && $exchangeReturn->items->count() > 0)
        <div class="mt-3">
            <h6 class="fw-bold mb-2 text-dark">Returned Items (Exchange)</h6>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th class="text-start" style="width: 5%">#</th>
                        <th class="text-start" style="width: 38%">Description</th>
                        <th class="text-center" style="width: 14%">Qty</th>
                        <th class="text-center" style="width: 10%">UOM</th>
                        <th class="text-end" style="width: 10%">Price</th>
                        <th class="text-end" style="width: 10%">Disc</th>
                        <th class="text-end" style="width: 13%">Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exchangeReturn->items as $retItem)
                        <tr>
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td class="text-start">
                                <div style="font-weight: bold; font-size: 12px; margin-bottom: 2px;">
                                    {{ $retItem->product->item_name ?? 'Unknown' }}
                                </div>
                            </td>
                            <td class="text-center font-monospace fw-bold">{{ (float)$retItem->qty }} Pcs</td>
                            <td class="text-center fw-bold">Pieces</td>
                            <td class="text-end font-monospace">Rs. {{ number_format($retItem->price, 2) }}</td>
                            <td class="text-end text-muted">—</td>
                            <td class="text-end font-monospace fw-bold text-danger">-Rs. {{ number_format($retItem->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Footer / Totals Section -->
        <div class="row mt-4">
            <div class="col-7">
                <div class="terms-box pt-2">
                    <p class="fw-bold mb-1 text-dark">Terms & Conditions:</p>
                    <ul style="font-size: 10px; color: #475569;">
                        @php
                            $invoiceTerms = \App\Models\Setting::get('invoice_terms', "10% will be deducted on return of purchase goods within 7 days.\nLoose & Water Soak products will not be RETURNED.\nPlease bring this invoice for any returns or exchanges.");
                            $termLines = explode("\n", $invoiceTerms);
                        @endphp
                        @foreach($termLines as $line)
                            @if(trim($line))
                                <li>{{ trim($line) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4 pt-2">
                    <div class="signature-area">
                        Authorized Signature
                    </div>
                    <div class="small text-muted mt-1 font-monospace" style="font-size: 10px;">
                        Printed on: {{ date('d/m/Y h:i A') }}
                    </div>
                </div>
            </div>

            <div class="col-5">
                <div class="info-box" style="border: 1px solid #cbd5e1; padding: 10px;">
                    <table class="totals-table">
                        @php
                            $grossTotal  = collect($saleItems)->sum('total');
                            $totalDisc   = collect($saleItems)->sum('discount_amount');
                            $netBill     = $sale->total_net;
                            $paidAmount  = (float)($sale->cash ?? 0);
                            $finalBal    = $previousBalance + $netBill - $paidAmount;
                        @endphp

                        @if ($totalDisc > 0)
                        <tr>
                            <td class="text-muted">Gross Total</td>
                            <td class="text-end text-muted font-monospace">Rs. {{ number_format($grossTotal + $totalDisc, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Discount</td>
                            <td class="text-end text-danger font-monospace">- Rs. {{ number_format($totalDisc, 2) }}</td>
                        </tr>
                        @endif

                        @if ($exchangeReturnedAmount > 0)
                        <tr>
                            <td class="text-muted">Return Value</td>
                            <td class="text-end text-danger font-monospace">- Rs. {{ number_format($exchangeReturnedAmount, 2) }}</td>
                        </tr>
                        @endif

                        @php
                            $finalPayable = $netBill - $exchangeReturnedAmount;
                        @endphp
                        <tr>
                            <td class="fw-bold text-dark fs-6" style="border-top: 2px solid #1e293b; padding-top: 8px;">
                                {{ $finalPayable < 0 ? 'Refund To Customer' : 'Net Payable' }}
                            </td>
                            <td class="text-end fw-bold text-dark fs-6 font-monospace" style="border-top: 2px solid #1e293b; padding-top: 8px;">
                                Rs. {{ number_format(abs($finalPayable), 2) }}
                            </td>
                        </tr>

                        @if (round(abs($previousBalance), 2) > 0)
                            <tr style="border-bottom: 2px solid #eee;">
                                <td class="text-muted">Previous Balance</td>
                                <td class="text-end text-muted font-monospace">
                                    Rs. {{ number_format(abs($previousBalance), 2) }}
                                    <small class="fw-bold">{{ $previousBalance >= 0 ? 'Dr' : 'Cr' }}</small>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="fw-semibold">Amount Paid</td>
                            <td class="text-end text-success fw-bold font-monospace">Rs. {{ number_format($paidAmount, 2) }}</td>
                        </tr>
                        @php
                            $finalBal = $previousBalance + $finalPayable - $paidAmount;
                        @endphp
                        <tr class="closing-bal">
                            <td class="fw-bold text-dark py-2">Closing Balance</td>
                            <td class="text-end fw-bold text-dark py-2 font-monospace">
                                Rs. {{ number_format(abs($finalBal), 2) }} <span class="badge bg-secondary text-white ms-1" style="font-size: 10px;">{{ $finalBal >= 0 ? 'Dr' : 'Cr' }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="text-end mt-2">
                    <small class="text-muted fst-italic font-monospace" style="font-size: 10px;">{{ Str::limit($sale->total_amount_Words, 70) }}</small>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
