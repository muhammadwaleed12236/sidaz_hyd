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
        <td class="text-center font-monospace fw-bold">
            {{ $sale->total_items > 0 ? $sale->total_items : $sale->qty }}
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
        <td class="pe-3 text-center">
            <div class="d-flex flex-wrap gap-1 align-items-center justify-content-center">
                @if ($sale->sale_status === 'draft' || $sale->sale_status === 'booked')
                    {{-- Draft / Booked Actions --}}
                    <form action="{{ route('sales.confirm', $sale->id) }}" method="POST" class="d-inline confirm-booking-form">
                        @csrf
                        <button type="button" class="btn btn-xs btn-success confirm-booking-btn shadow-sm">
                            <i class="fas fa-check-circle me-1"></i>Confirm
                        </button>
                    </form>
                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-xs btn-outline-primary shadow-sm" title="Edit Sale">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank" class="btn btn-xs btn-primary shadow-sm" title="View Invoice">
                        <i class="fas fa-file-invoice me-1"></i>Invoice
                    </a>
                    <a href="{{ route('sales.invoice', ['id' => $sale->id, 'type' => 'estimate']) }}" target="_blank" class="btn btn-xs btn-outline-secondary shadow-sm" title="Estimate">
                        <i class="fas fa-calculator me-1"></i>Est.
                    </a>
                @else
                    {{-- Posted Actions --}}
                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-xs btn-outline-primary shadow-sm" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('sales.invoice', $sale->id) }}" target="_blank" class="btn btn-xs btn-primary shadow-sm" title="View Invoice">
                        <i class="fas fa-file-invoice me-1"></i>Invoice
                    </a>
                    <a href="{{ route('sales.invoice', ['id' => $sale->id, 'type' => 'estimate']) }}" target="_blank" class="btn btn-xs btn-outline-secondary shadow-sm" title="Estimate">
                        <i class="fas fa-calculator me-1"></i>Est.
                    </a>
                    <a href="{{ route('sales.dc', $sale->id) }}" target="_blank" class="btn btn-xs btn-warning text-dark shadow-sm" title="Delivery Challan">
                        <i class="fas fa-shipping-fast me-1"></i>DC
                    </a>
                    <a href="{{ route('sales.receipt', $sale->id) }}" target="_blank" class="btn btn-xs btn-success shadow-sm" title="Thermal Receipt">
                        <i class="fas fa-receipt me-1"></i>Receipt
                    </a>
                    @if ($sale->sale_status !== 'returned')
                        <a href="{{ route('sale.return.show', $sale->id) }}" class="btn btn-xs btn-outline-danger shadow-sm" title="Sale Return">
                            <i class="fas fa-undo me-1"></i>Return
                        </a>
                    @else
                        <button class="btn btn-xs btn-secondary" disabled>Returned</button>
                    @endif
                @endif
            </div>
        </td>
    </tr>
@endforeach
