@extends('admin_panel.layout.app')

@section('content')
<style>
    :root {
        --rpt-primary:    #4f46e5;
        --rpt-primary-lt: #eef2ff;
        --rpt-success:    #059669;
        --rpt-success-lt: #ecfdf5;
        --rpt-warning:    #d97706;
        --rpt-warning-lt: #fffbeb;
        --rpt-danger:     #dc2626;
        --rpt-danger-lt:  #fef2f2;
        --rpt-info:       #0284c7;
        --rpt-info-lt:    #e0f2fe;
        --rpt-border:     #e2e8f0;
        --rpt-bg:         #f8fafc;
        --rpt-card-bg:    #ffffff;
        --rpt-text:       #1e293b;
        --rpt-muted:      #64748b;
        --rpt-radius:     12px;
        --rpt-shadow:     0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    }

    .rpt-page { background: var(--rpt-bg); min-height: calc(100vh - 80px); padding: 20px 0; }

    /* Mode Selector Pills */
    .mode-pills { display: flex; gap: 8px; margin-bottom: 16px; }
    .mode-pill {
        padding: 8px 18px; border-radius: 20px; border: 1px solid var(--rpt-border);
        background: #fff; font-size: .82rem; font-weight: 700; color: var(--rpt-muted);
        cursor: pointer; transition: all .15s ease; display: inline-flex; align-items: center; gap: 6px;
    }
    .mode-pill.active, .mode-pill:hover { background: var(--rpt-primary); color: #fff; border-color: var(--rpt-primary); box-shadow: 0 4px 12px rgba(79,70,229,.2); }

    /* Filter Card */
    .rpt-filter-card {
        background: #ffffff; border-radius: var(--rpt-radius); border: 1px solid var(--rpt-border);
        box-shadow: var(--rpt-shadow); padding: 16px 20px; margin-bottom: 20px;
    }
    .rpt-flabel { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--rpt-muted); margin-bottom: 4px; display: block; }
    .rpt-finput { width: 100%; height: 38px; border: 1px solid var(--rpt-border); border-radius: 8px; font-size: .84rem; padding: 0 10px; color: var(--rpt-text); outline: none; background: #fff; }

    /* KPI Summary Cards */
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
    .kpi-card {
        border-radius: var(--rpt-radius); padding: 16px 20px; color: #fff;
        display: flex; align-items: center; justify-content: space-between;
        box-shadow: var(--rpt-shadow); transition: transform .2s ease;
    }
    .kpi-card:hover { transform: translateY(-2px); }

    /* Status Badges */
    .status-healthy { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; border-radius: 12px; padding: 2px 8px; font-size: .7rem; font-weight: 700; }
    .status-low     { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 12px; padding: 2px 8px; font-size: .7rem; font-weight: 700; }
    .status-out     { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 12px; padding: 2px 8px; font-size: .7rem; font-weight: 700; }

    .badge-in  { background: var(--rpt-success-lt); color: var(--rpt-success); border: 1px solid #a7f3d0; border-radius: 12px; padding: 2px 8px; font-weight: 700; font-size: .74rem; }
    .badge-out { background: var(--rpt-danger-lt);  color: var(--rpt-danger);  border: 1px solid #fecaca; border-radius: 12px; padding: 2px 8px; font-weight: 700; font-size: .74rem; }

    #rawMaterialTable th { background: #f8fafc !important; color: #475569 !important; font-size: .68rem; text-transform: uppercase; font-weight: 700; letter-spacing: .5px; padding: 10px 12px; }
    #rawMaterialTable td { font-size: .8rem; vertical-align: middle; padding: 9px 12px; }

    @media (max-width: 768px) {
        .kpi-grid { grid-template-columns: repeat(2, 1fr) !important; }
        .mode-pills { flex-wrap: wrap; }
    }
</style>

<div class="rpt-page">
<div class="container-fluid px-3">

    {{-- Page Header & Mode Switcher --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-0"><i class="fas fa-vial text-primary me-2"></i>Raw Material Detail &amp; Movement Report</h4>
            <small class="text-muted">Comprehensive inventory stock levels, valuation summary, purchase inflows, and production consumptions</small>
        </div>

        {{-- Mode Pills --}}
        <div class="mode-pills">
            <div class="mode-pill active" data-mode="summary">
                <i class="fas fa-chart-pie"></i> Valuation &amp; Stock Summary
            </div>
            <div class="mode-pill" data-mode="ledger">
                <i class="fas fa-list-alt"></i> Movement Ledger History
            </div>
        </div>
    </div>

    {{-- KPI Summary Cards --}}
    <div class="kpi-grid">
        <div class="kpi-card" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
            <div>
                <div style="font-size:.7rem; font-weight:700; text-transform:uppercase; opacity:.85;">Grand Stock Value</div>
                <div class="fs-4 fw-bold mt-1" id="kpiGrandStockValue">Rs 0.00</div>
            </div>
            <div style="font-size:24px; opacity:.8;"><i class="fas fa-coins"></i></div>
        </div>

        <div class="kpi-card" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
            <div>
                <div style="font-size:.7rem; font-weight:700; text-transform:uppercase; opacity:.85;">Total Raw Materials</div>
                <div class="fs-4 fw-bold mt-1" id="kpiTotalItems">0</div>
            </div>
            <div style="font-size:24px; opacity:.8;"><i class="fas fa-flask"></i></div>
        </div>

        <div class="kpi-card" style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);">
            <div>
                <div style="font-size:.7rem; font-weight:700; text-transform:uppercase; opacity:.85;">Total Stock Quantity</div>
                <div class="fs-4 fw-bold mt-1" id="kpiTotalStockQty">0</div>
            </div>
            <div style="font-size:24px; opacity:.8;"><i class="fas fa-cubes"></i></div>
        </div>

        <div class="kpi-card" style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);">
            <div>
                <div style="font-size:.7rem; font-weight:700; text-transform:uppercase; opacity:.85;">Low Stock Alerts</div>
                <div class="fs-4 fw-bold mt-1" id="kpiLowStockAlerts">0</div>
            </div>
            <div style="font-size:24px; opacity:.8;"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="rpt-filter-card">
        <form id="filterForm">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="rpt-flabel">Raw Material</label>
                    <select name="raw_material_id" id="filterRawMaterial" class="rpt-finput select2">
                        <option value="">-- All Raw Materials --</option>
                        @foreach($rawMaterials as $rm)
                            <option value="{{ $rm->id }}">{{ $rm->name }} ({{ $rm->code ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="rpt-flabel">Date From</label>
                    <input type="date" name="date_from" id="filterDateFrom" class="rpt-finput">
                </div>

                <div class="col-md-2">
                    <label class="rpt-flabel">Date To</label>
                    <input type="date" name="date_to" id="filterDateTo" class="rpt-finput">
                </div>

                <div class="col-md-3">
                    <label class="rpt-flabel">Search</label>
                    <input type="text" id="customSearchInput" class="rpt-finput" placeholder="Type material name or code...">
                </div>

                <div class="col-md-2 text-end">
                    <button type="button" id="btnFilter" class="btn btn-primary btn-sm fw-bold px-3 py-2 w-100 mb-1">
                        <i class="fas fa-filter me-1"></i> Apply Filter
                    </button>
                    <button type="button" id="btnPrint" class="btn btn-outline-secondary btn-sm fw-bold px-2 py-1 w-100">
                        <i class="fas fa-print me-1"></i> Print / Export
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Table Container --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="rawMaterialTable">
                    <thead class="bg-light">
                        <tr id="tableHeadRow">
                            <!-- Dynamic Table Headings via JS -->
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Dynamic Rows via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>

@endsection

@section('js')
<script>
$(document).ready(function() {
    let currentMode = 'summary';

    $('.select2').select2({ width: '100%' });

    // Mode switch
    $('.mode-pill').on('click', function() {
        $('.mode-pill').removeClass('active');
        $(this).addClass('active');
        currentMode = $(this).data('mode');
        fetchReport();
    });

    $('#btnFilter').on('click', function() {
        fetchReport();
    });

    $('#customSearchInput').on('keyup', function() {
        let val = $(this).val().toLowerCase();
        $('#tableBody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
        });
    });

    $('#btnPrint').on('click', function() {
        window.print();
    });

    function fetchReport() {
        $('#tableBody').html('<tr><td colspan="9" class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary me-2"></i>Loading Report...</td></tr>');

        $.ajax({
            url: "{{ route('report.raw_material_detail.fetch') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                raw_material_id: $('#filterRawMaterial').val(),
                date_from: $('#filterDateFrom').val(),
                date_to: $('#filterDateTo').val(),
                report_mode: currentMode
            },
            success: function(res) {
                if (res.ok) {
                    if (res.mode === 'summary') {
                        renderSummaryTable(res);
                    } else {
                        renderLedgerTable(res);
                    }
                }
            },
            error: function(err) {
                $('#tableBody').html('<tr><td colspan="9" class="text-center text-danger py-4">Error loading report data.</td></tr>');
            }
        });
    }

    function renderSummaryTable(res) {
        $('#kpiGrandStockValue').text('Rs. ' + (res.summary.total_stock_value || 0).toLocaleString('en-PK', {minimumFractionDigits:2, maximumFractionDigits:2}));
        $('#kpiTotalItems').text(res.summary.total_items || 0);
        $('#kpiTotalStockQty').text((res.summary.total_stock_qty || 0).toLocaleString('en-PK'));
        $('#kpiLowStockAlerts').text(res.summary.total_low_stock || 0);

        let headHtml = `
            <th>#</th>
            <th>Code</th>
            <th>Raw Material Name</th>
            <th class="text-center">Unit</th>
            <th class="text-end">Cost Rate</th>
            <th class="text-end">Current Stock</th>
            <th class="text-end">Stock Value (PKR)</th>
            <th class="text-end">Purchased (In)</th>
            <th class="text-end">Consumed (Out)</th>
            <th class="text-center">Status</th>
        `;
        $('#tableHeadRow').html(headHtml);

        let bodyHtml = '';
        if (res.data.length === 0) {
            bodyHtml = '<tr><td colspan="10" class="text-center py-4 text-muted">No raw material stock records found.</td></tr>';
        } else {
            res.data.forEach((row, idx) => {
                let badgeClass = 'status-healthy';
                let statusText = 'Healthy Stock';
                if (row.status === 'out') {
                    badgeClass = 'status-out';
                    statusText = 'Out of Stock';
                } else if (row.status === 'low') {
                    badgeClass = 'status-low';
                    statusText = 'Low Stock Alert';
                }

                bodyHtml += `
                    <tr>
                        <td class="text-muted font-weight-bold">${idx + 1}</td>
                        <td class="font-weight-bold text-primary">${row.code}</td>
                        <td class="font-weight-bold text-dark">${row.name}</td>
                        <td class="text-center"><span class="badge bg-light text-dark border">${row.unit}</span></td>
                        <td class="text-end font-weight-bold">Rs ${parseFloat(row.price).toFixed(2)}</td>
                        <td class="text-end font-weight-bold text-info">${parseFloat(row.current_stock).toFixed(2)}</td>
                        <td class="text-end font-weight-bold text-success">Rs ${parseFloat(row.stock_value).toLocaleString('en-PK', {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                        <td class="text-end text-success fw-bold">+${parseFloat(row.purchased_qty).toFixed(2)}</td>
                        <td class="text-end text-danger fw-bold">-${parseFloat(row.consumed_qty).toFixed(2)}</td>
                        <td class="text-center"><span class="${badgeClass}">${statusText}</span></td>
                    </tr>
                `;
            });
        }
        $('#tableBody').html(bodyHtml);
    }

    function renderLedgerTable(res) {
        let headHtml = `
            <th>#</th>
            <th>Date & Time</th>
            <th>Raw Material</th>
            <th>Type</th>
            <th>Reference / Note</th>
            <th class="text-end">Quantity</th>
            <th class="text-center">Unit</th>
            <th class="text-end">Cost Rate</th>
            <th class="text-end">Total Amount</th>
        `;
        $('#tableHeadRow').html(headHtml);

        let bodyHtml = '';
        if (res.data.length === 0) {
            bodyHtml = '<tr><td colspan="9" class="text-center py-4 text-muted">No material stock movements found for selected filters.</td></tr>';
        } else {
            res.data.forEach((m, idx) => {
                let typeBadge = m.type === 'in' 
                    ? '<span class="badge-in"><i class="fas fa-arrow-down me-1"></i> PURCHASE IN</span>'
                    : '<span class="badge-out"><i class="fas fa-arrow-up me-1"></i> PRODUCTION OUT</span>';

                bodyHtml += `
                    <tr>
                        <td class="text-muted font-weight-bold">${idx + 1}</td>
                        <td class="small text-muted">${m.date}</td>
                        <td class="font-weight-bold text-dark">${m.raw_material_name} <small class="text-muted">(${m.raw_material_code})</small></td>
                        <td>${typeBadge}</td>
                        <td class="small">${m.ref_type}: ${m.note}</td>
                        <td class="text-end font-weight-bold ${m.type === 'in' ? 'text-success' : 'text-danger'}">${m.type === 'in' ? '+' : '-'}${parseFloat(m.qty).toFixed(2)}</td>
                        <td class="text-center"><span class="badge bg-light text-dark border">${m.unit}</span></td>
                        <td class="text-end">Rs ${parseFloat(m.unit_price).toFixed(2)}</td>
                        <td class="text-end font-weight-bold">Rs ${parseFloat(m.total_amount).toLocaleString('en-PK', {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                    </tr>
                `;
            });
        }
        $('#tableBody').html(bodyHtml);
    }

    // Initial load
    fetchReport();
});
</script>
@endsection
