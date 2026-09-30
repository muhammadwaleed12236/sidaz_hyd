@foreach ($sales as $sale)
    @php
        // Product Names
        $pNames = 'N/A';
        if ($sale->items && $sale->items->count() > 0) {
            $pNames = $sale->items
                ->map(fn($item) => optional($item->product)->item_name ?? '?')
                ->implode(', ');
        } elseif ($sale->product) {
            $pNames = $sale->product;
        }

        // Calculate Ordered vs Produced vs Remaining Pieces
        $totalOrderedPcs = 0;
        if ($sale->items && $sale->items->count() > 0) {
            foreach ($sale->items as $item) {
                $ordered = (float) ($item->total_pieces > 0 ? $item->total_pieces : ($item->qty * (optional($item->product)->pieces_per_box ?? 1)));
                $totalOrderedPcs += $ordered;
            }
        }
        $totalProducedPcs = (float) ($sale->relationLoaded('productionBatches') && $sale->productionBatches ? $sale->productionBatches->sum('quantity') : \App\Models\ProductionBatch::where('sale_id', $sale->id)->sum('quantity'));
        $remainingPcs = max(0, $totalOrderedPcs - $totalProducedPcs);

        // Status Styling
        $statusBadge = '<span class="badge bg-secondary text-white shadow-sm">Draft</span>';
        $isExchange = \Illuminate\Support\Str::startsWith($sale->reference, 'Exchange for');
        
        if ($sale->sale_status === 'delivered' || $sale->sale_status === 'posted' || $sale->sale_status === 'dispatched') {
            $statusBadge = '<span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>Delivered / Complete</span>';
        } elseif ($sale->sale_status === 'ready' || $sale->sale_status === 'ready_for_delivery') {
            $statusBadge = '<span class="badge bg-warning-subtle text-dark border border-warning px-2 py-1"><i class="fas fa-box me-1"></i>Ready</span>';
        } elseif ($sale->sale_status === 'booked' || $sale->sale_status === 'sale_order' || $sale->is_booking || $sale->sale_status === 'pending') {
            $statusBadge = '<span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1"><i class="fas fa-file-invoice me-1"></i>Sale Order</span>';
        } elseif ($sale->sale_status === 'returned' || $sale->sale_status == 1) {
            $statusBadge = '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">Returned</span>';
        } else {
            $statusBadge = '<span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1"><i class="fas fa-file-invoice me-1"></i>Sale Order</span>';
        }

        // Add Batch / Production status indicator badge
        if ($totalOrderedPcs > 0) {
            if ($totalProducedPcs == 0) {
                $statusBadge .= '<br><span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1 mt-1 d-inline-block" style="font-size:10px;"><i class="fas fa-industry me-1"></i>0 / '.number_format($totalOrderedPcs).' Produced</span>';
            } elseif ($totalProducedPcs > 0 && $remainingPcs > 0) {
                $statusBadge .= '<br><span class="badge bg-warning-subtle text-dark border border-warning px-2 py-1 mt-1 d-inline-block" style="font-size:10px;" title="Ordered: '.number_format($totalOrderedPcs).' | Produced: '.number_format($totalProducedPcs).' | Remaining: '.number_format($remainingPcs).'"><i class="fas fa-hourglass-half me-1"></i>Partial: '.number_format($totalProducedPcs).'/'.number_format($totalOrderedPcs).' Pcs ('.number_format($remainingPcs).' Rem.)</span>';
            } elseif ($totalProducedPcs >= $totalOrderedPcs) {
                $statusBadge .= '<br><span class="badge bg-success-subtle text-success border border-success px-2 py-1 mt-1 d-inline-block" style="font-size:10px;"><i class="fas fa-check-circle me-1"></i>Batch Ready ('.number_format($totalProducedPcs).'/'.number_format($totalOrderedPcs).' Pcs)</span>';
            }
        }

        // Check for returns
        if ($sale->returns && $sale->returns->count() > 0) {
            $statusBadge .= '<br><small class="badge bg-danger text-white mt-1"><i class="fas fa-undo-alt me-1"></i> Partial Return</small>';
        }
    @endphp
    <tr>
        <td class="ps-3 fw-bold text-primary font-monospace">#{{ $sale->invoice_no ?: $sale->id }}</td>
        @php
            $custDisplayName = optional($sale->customer_relation)->customer_name;
            if (!$custDisplayName || trim($custDisplayName) === '' || strtolower(trim($custDisplayName)) === 'n/a') {
                $custDisplayName = !empty($sale->walkin_name) ? $sale->walkin_name : 'Walk-in Customer';
            }
            $avatarLetter = strtoupper(substr($custDisplayName, 0, 1));
        @endphp
        <td>
            <div class="d-flex align-items-center">
                <div class="avatar-circle bg-primary-subtle text-primary me-2 fw-bold d-flex align-items-center justify-content-center rounded-circle"
                    style="width: 32px; height: 32px; font-size: 13px;">
                    {{ $avatarLetter }}
                </div>
                <div>
                    <span class="fw-bold text-dark d-block">{{ $custDisplayName }}</span>
                </div>
            </div>
        </td>
        <td title="{{ $pNames }}" class="text-muted small">
            {{ \Illuminate\Support\Str::limit($pNames, 40) }}
        </td>
        <td class="text-center font-monospace">
            <div class="fw-bold text-dark">{{ number_format($totalOrderedPcs > 0 ? $totalOrderedPcs : ($sale->total_items > 0 ? $sale->total_items : $sale->qty)) }} Pcs</div>
            @if ($totalOrderedPcs > 0)
                <div class="small mt-1" style="font-size: 10px;">
                    <span class="text-success fw-bold" title="Produced Quantity"><i class="fas fa-check me-1"></i>{{ number_format($totalProducedPcs) }} Done</span>
                    @if ($remainingPcs > 0)
                        <br><span class="text-danger fw-bold" title="Remaining Quantity to Produce & Deliver"><i class="fas fa-clock me-1"></i>{{ number_format($remainingPcs) }} Rem.</span>
                    @endif
                </div>
            @endif
        </td>
        @php
            $inline_val = $sale->items ? $sale->items->sum('discount_amount') : 0;
            $bill_amount = $sale->total_bill_amount > 0 ? $sale->total_bill_amount : (float) $sale->per_total;
            $gross_subtotal = $bill_amount + $inline_val;
            $inline_pct = $gross_subtotal > 0 ? ($inline_val / $gross_subtotal) * 100 : 0;
        @endphp
        <td class="text-end fw-bold text-dark font-monospace">
            Rs. {{ number_format($gross_subtotal, 2) }}
        </td>
        <td class="text-end text-dark font-monospace">
            Rs. {{ number_format($inline_val, 2) }}
            @if ($inline_val > 0)
                <div class="text-muted small mt-1" style="font-size: 10px;">({{ number_format($inline_pct, 1) }}%)</div>
            @endif
        </td>
        <td class="text-end text-dark font-monospace">
            @if ($sale->total_extradiscount > 0)
                @php
                    $add_val = $sale->total_extradiscount;
                    $add_pct = $bill_amount > 0 ? ($add_val / $bill_amount) * 100 : 0;
                @endphp
                <span class="badge rounded-pill bg-warning-subtle text-dark border border-warning px-2 py-1 fw-bold" style="font-size: 11px;">
                    <i class="fas fa-tag me-1"></i> Rs. {{ number_format($add_val, 2) }}
                </span>
                <div class="text-muted small mt-1" style="font-size: 10px;">({{ number_format($add_pct, 1) }}%)</div>
            @else
                <span class="text-muted">Rs. 0.00</span>
            @endif
        </td>
        <td class="text-end text-success fw-bold font-monospace">
            @if (isset($isExchange) && $isExchange)
                @php
                    $collected = $sale->cash - $sale->change;
                    $refunded = 0;
                    if ($collected <= 0) {
                        $refundPayment = \App\Models\CustomerPayment::where('note', 'Refund Paid for POS Exchange #'.$sale->invoice_no)->first();
                        if ($refundPayment) {
                            $refunded = $refundPayment->amount;
                        }
                    }
                @endphp
                
                @if ($collected > 0)
                    Rs. {{ number_format($collected, 2) }}
                @elseif ($refunded > 0)
                    <span class="text-danger">-Rs. {{ number_format($refunded, 2) }}</span>
                @else
                    Rs. 0.00
                @endif
                <br><span class="badge bg-info text-white px-1 py-0 mt-1" style="font-size: 10px;"><i class="fas fa-exchange-alt me-1"></i>Exchange</span>
            @else
                Rs. {{ number_format($sale->total_net, 2) }}
            @endif
        </td>
        <td class="text-nowrap small text-muted">
            {{ $sale->created_at->format('d/m/Y') }}
        </td>
        <td>{!! $statusBadge !!}</td>
        <td class="pe-3 text-center position-relative">
            <div class="dropdown d-inline-block">
                <button class="btn btn-xs btn-outline-secondary dropdown-toggle shadow-sm fw-bold px-2 py-1 sale-action-dropdown-btn" type="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v me-1 text-primary"></i> Action
                </button>
                <ul class="dropdown-menu dropdown-menu-right dropdown-menu-end shadow border-0 py-2" style="font-size: 12px; min-width: 180px; z-index: 99999 !important;">
                    {{-- 1. Produce Batch Action --}}
                    @if ($remainingPcs > 0 && $sale->sale_status !== 'delivered' && $sale->sale_status !== 'posted' && $sale->sale_status !== 'returned')
                        <li>
                            <a href="{{ route('production.create', ['sale_id' => $sale->id]) }}" class="dropdown-item text-info fw-bold d-flex align-items-center gap-2">
                                <i class="fas fa-industry"></i> Produce Batch ({{ number_format($remainingPcs) }} Pcs)
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                    @endif

                    {{-- 2. Confirm Booking (Draft/Booked) --}}
                    @if ($sale->sale_status === 'draft' || $sale->sale_status === 'booked')
                        <li>
                            <form action="{{ route('sales.confirm', $sale->id) }}" method="POST" class="d-inline confirm-booking-form">
                                @csrf
                                <button type="button" class="dropdown-item text-success fw-bold d-flex align-items-center gap-2 confirm-booking-btn">
                                    <i class="fas fa-check-circle"></i> Confirm Sale
                                </button>
                            </form>
                        </li>
                    @endif

                    {{-- 3. Invoice & Estimate --}}
                    <li>
                        <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank" class="dropdown-item text-primary fw-bold d-flex align-items-center gap-2">
                            <i class="fas fa-file-invoice"></i> View Invoice
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sales.invoice', ['id' => $sale->id, 'type' => 'estimate']) }}" target="_blank" class="dropdown-item text-secondary d-flex align-items-center gap-2">
                            <i class="fas fa-calculator"></i> View Estimate
                        </a>
                    </li>

                    {{-- 4. DC & Receipt (For Posted/Delivered) --}}
                    @if ($sale->sale_status !== 'draft' && $sale->sale_status !== 'booked')
                        <li>
                            <a href="{{ route('sales.dc', $sale->id) }}" target="_blank" class="dropdown-item text-warning fw-bold d-flex align-items-center gap-2" style="color: #b45309 !important;">
                                <i class="fas fa-shipping-fast"></i> Delivery Challan (DC)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('sales.receipt', $sale->id) }}" target="_blank" class="dropdown-item text-success fw-bold d-flex align-items-center gap-2">
                                <i class="fas fa-receipt"></i> Thermal Receipt
                            </a>
                        </li>
                    @endif

                    <li><hr class="dropdown-divider my-1"></li>

                    {{-- 5. Edit Sale --}}
                    <li>
                        <a href="{{ route('sales.edit', $sale->id) }}" class="dropdown-item text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-edit text-muted"></i> Edit Sale
                        </a>
                    </li>

                    {{-- 6. Sale Return --}}
                    @if ($sale->sale_status !== 'draft' && $sale->sale_status !== 'booked')
                        @if ($sale->sale_status !== 'returned')
                            <li>
                                <a href="{{ route('sale.return.show', $sale->id) }}" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                    <i class="fas fa-undo"></i> Sale Return
                                </a>
                            </li>
                        @else
                            <li>
                                <span class="dropdown-item disabled text-muted d-flex align-items-center gap-2">
                                    <i class="fas fa-ban"></i> Returned
                                </span>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </td>
    </tr>
@endforeach
