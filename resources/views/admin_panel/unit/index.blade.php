@extends('admin_panel.layout.app')
@section('content')

@if (session('success'))
    <script>
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#4f46e5'
        });
    });
    </script>
@endif

<style>
    /* ── ERP COMPACT DESIGN SYSTEM ── */
    .erp-page { background: #f8fafc; min-height: calc(100vh - 60px); padding: 12px 0; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    
    :root {
        --erp-primary:    #4f46e5;
        --erp-primary-lt: #eef2ff;
        --erp-success:    #10b981;
        --erp-success-lt: #ecfdf5;
        --erp-warning:    #f59e0b;
        --erp-warning-lt: #fffbeb;
        --erp-danger:     #ef4444;
        --erp-danger-lt:  #fef2f2;
        --erp-border:     #e2e8f0;
        --erp-bg:         #f8fafc;
        --erp-card-bg:    #ffffff;
        --erp-text:       #0f172a;
        --erp-muted:      #64748b;
        --erp-radius:     8px;
        --erp-shadow:     0 1px 3px rgba(15,23,42,0.05);
    }

    .erp-card {
        background: var(--erp-card-bg);
        border-radius: var(--erp-radius);
        border: 1px solid var(--erp-border);
        box-shadow: var(--erp-shadow);
        overflow: hidden;
    }

    .erp-card-header {
        padding: 12px 18px;
        border-bottom: 1px solid var(--erp-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        background: #ffffff;
    }
    .page-head-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--erp-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .head-kpis {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: 8px;
    }
    .kpi-pill {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .kpi-pill strong { color: var(--erp-text); font-weight: 700; }
    .kpi-pill.success { background: var(--erp-success-lt); color: #047857; border-color: #a7f3d0; }
    .kpi-pill.danger { background: var(--erp-danger-lt); color: #b91c1c; border-color: #fecaca; }

    .erp-hdr-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }
    .btn-hdr {
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.81rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all .15s;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        height: 34px;
        box-sizing: border-box;
    }
    .btn-hdr-primary { background: #c026d3; color: #fff; border-color: #c026d3; box-shadow: 0 1px 3px rgba(192,38,211,0.2); }
    .btn-hdr-primary:hover { background: #a21caf; border-color: #a21caf; color: #fff; }

    /* Desktop Table */
    .erp-table-wrap { padding: 0; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    #default-datatable {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 0.83rem;
        margin-bottom: 0 !important;
    }
    #default-datatable thead th {
        background: #f1f5f9 !important;
        color: #475569 !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.71rem;
        letter-spacing: 0.5px;
        padding: 9px 12px;
        border-bottom: 1px solid var(--erp-border) !important;
        white-space: nowrap;
    }
    #default-datatable tbody td {
        padding: 8px 12px;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: var(--erp-text);
        vertical-align: middle;
        white-space: nowrap;
    }
    #default-datatable tbody tr:hover { background: #f8fafc !important; }

    /* Badges */
    .unit-id-badge { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; font-family: monospace; }
    .unit-code-badge { background: #fdf4ff; color: #c026d3; font-weight: 600; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; border: 1px solid #fae8ff; }
    .type-badge { background: #eef2ff; color: #4f46e5; font-weight: 600; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; }
    .base-badge { background: #fffbeb; color: #b45309; font-weight: 600; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; }
    
    .status-badge { padding: 3px 10px; border-radius: 12px; font-size: 0.72rem; font-weight: 700; white-space: nowrap; }
    .status-active { background: var(--erp-success-lt); color: #047857; border: 1px solid #a7f3d0; }
    .status-inactive { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

    /* Action buttons */
    .btn-act {
        border-radius: 4px; padding: 4px 8px; font-size: 0.75rem;
        font-weight: 600; display: inline-flex; align-items: center; gap: 4px;
        border: 1px solid transparent; transition: all .12s; cursor: pointer;
        line-height: 1.3; white-space: nowrap; flex-shrink: 0; height: 28px;
    }
    .btn-act-edit { background: var(--erp-primary-lt); color: var(--erp-primary); border-color: #c7d2fe; }
    .btn-act-edit:hover { background: var(--erp-primary); color: #fff; }
    .btn-act-deact { background: var(--erp-danger-lt); color: #b91c1c; border-color: #fecaca; }
    .btn-act-deact:hover { background: #ef4444; color: #fff; }

    /* Switch */
    .switch { position: relative; display: inline-block; width: 44px; height: 22px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .3s; border-radius: 22px; }
    .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
    input:checked + .slider { background-color: #10b981; }
    input:checked + .slider:before { transform: translateX(22px); }

    /* Mobile view cards */
    .mobile-unit-cards { display: none; padding: 10px; }
    @media (max-width: 768px) {
        .erp-page { padding: 8px 0; }
        .erp-card-header { flex-direction: column; align-items: flex-start; gap: 8px; padding: 10px 12px; }
        .erp-hdr-actions { width: 100%; }
        .btn-hdr { width: 100%; justify-content: center; height: 34px; font-size: 0.8rem; }
        .erp-table-wrap { display: none !important; }
        .mobile-unit-cards { display: flex; flex-direction: column; gap: 8px; }
    }

    .unit-mcard {
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: 8px;
        padding: 10px 12px;
        box-shadow: 0 1px 3px rgba(15,23,42,0.03);
    }
    .unit-mcard-hd { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .unit-mcard-body {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .unit-mcard-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 6px !important;
        margin-top: 8px;
        width: 100%;
    }
    .unit-mcard-actions .btn-act { width: 100% !important; justify-content: center !important; height: 32px !important; font-size: 0.76rem !important; border-radius: 6px !important; }
</style>

<div class="erp-page">
    <div class="container-fluid px-2">
        <div class="erp-card">
            
            {{-- Header Bar --}}
            <div class="erp-card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h1 class="page-head-title"><i class="fas fa-balance-scale me-1" style="color: #c026d3;"></i> Units of Measurement</h1>
                    <div class="head-kpis">
                        <span class="kpi-pill">Total: <strong>{{ count($units) }}</strong></span>
                        <span class="kpi-pill success">Active: <strong>{{ $units->where('status', 1)->count() }}</strong></span>
                        <span class="kpi-pill danger">Inactive: <strong>{{ $units->where('status', 0)->count() }}</strong></span>
                    </div>
                </div>
                <div class="erp-hdr-actions">
                    @can('units.add')
                        <button type="button" class="btn-hdr btn-hdr-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                            <i class="fas fa-plus"></i> Add Unit
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Desktop Table View --}}
            <div class="erp-table-wrap">
                <table id="default-datatable" class="table align-middle nowrap">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">ID</th>
                            <th>Unit Name</th>
                            <th>Short Code</th>
                            <th>Type</th>
                            <th>Base Unit</th>
                            <th>Conv. Factor</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr class="unit-row-item">
                                <td class="text-center id"><span class="unit-id-badge">#{{ $unit->id }}</span></td>
                                <td class="name fw-semibold text-dark">{{ $unit->name }}</td>
                                <td class="code"><span class="unit-code-badge">{{ $unit->short_code }}</span></td>
                                <td class="type"><span class="type-badge">{{ $unit->unit_type }}</span></td>
                                
                                <td class="base" data-baseid="{{ $unit->base_unit }}">
                                    @if($unit->baseUnit)
                                        <span class="base-badge">{{ $unit->baseUnit->name }} ({{ $unit->baseUnit->short_code }})</span>
                                    @else
                                        <span class="text-muted small">None (Base)</span>
                                    @endif
                                </td>

                                <td class="factor fw-semibold text-dark">{{ $unit->conversion_factor }}</td>

                                <td class="text-center status" data-status="{{ $unit->status }}">
                                    @if($unit->status)
                                        <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 7px;"></i> Active</span>
                                    @else
                                        <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 7px;"></i> Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @include('admin_panel.partials.action_buttons', [
                                        'editRoute' => route('store.Unit'),
                                        'deleteRoute' => route('delete.Unit', $unit->id),
                                        'editIsLink' => false,
                                        'permissions' => [
                                            'edit' => 'units.edit',
                                            'delete' => 'units.delete',
                                        ],
                                        'deleteMsg' => 'Are you sure you want to delete this unit?',
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>{{-- /erp-table-wrap --}}

            {{-- Mobile Cards View (< 768px) --}}
            <div class="mobile-unit-cards">
                @foreach ($units as $unit)
                    <div class="unit-mcard unit-row-item">
                        <div class="unit-mcard-hd">
                            <div>
                                <span class="unit-code-badge">{{ $unit->short_code }}</span>
                                <div class="fw-bold text-dark fs-6 mt-1">{{ $unit->name }}</div>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <span class="type-badge">{{ $unit->unit_type }}</span>
                                    @if($unit->baseUnit)
                                        <span class="base-badge">Base: {{ $unit->baseUnit->short_code }}</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($unit->status)
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="unit-mcard-body">
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:var(--erp-muted); text-transform:uppercase;">Conversion Factor</div>
                                <div class="fw-bold text-dark fs-6">{{ $unit->conversion_factor }}</div>
                            </div>
                            <div class="text-end">
                                <span class="unit-id-badge">ID: #{{ $unit->id }}</span>
                            </div>
                        </div>

                        {{-- Hidden elements for JS compatibility --}}
                        <span class="d-none id">#{{ $unit->id }}</span>
                        <span class="d-none name">{{ $unit->name }}</span>
                        <span class="d-none code">{{ $unit->short_code }}</span>
                        <span class="d-none type">{{ $unit->unit_type }}</span>
                        <span class="d-none base" data-baseid="{{ $unit->base_unit }}"></span>
                        <span class="d-none factor">{{ $unit->conversion_factor }}</span>
                        <span class="d-none status" data-status="{{ $unit->status }}"></span>

                        <div class="unit-mcard-actions">
                            @can('units.edit')
                                <button type="button" class="btn-act btn-act-edit edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            @endcan
                            @can('units.delete')
                                <form action="{{ route('delete.Unit', $unit->id) }}" method="GET" class="d-inline w-100" onsubmit="return confirm('Are you sure you want to delete this unit?')">
                                    <button type="submit" class="btn-act btn-act-deact w-100">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>

        </div>{{-- /erp-card --}}
    </div>
</div>

{{-- Add/Edit Unit Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 10px;">
            <div class="modal-header bg-light px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark fs-6" id="exampleModalLabel"><span id="modalTitleText">Add Unit</span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="myform" action="{{ route('store.Unit') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold text-dark small d-block mb-1">Unit Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm px-3 py-2 w-100" id="name" placeholder="e.g. Kilogram" required style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6">
                            <label for="short_code" class="form-label fw-semibold text-dark small d-block mb-1">Short Code <span class="text-danger">*</span></label>
                            <input type="text" name="short_code" class="form-control form-control-sm px-3 py-2 w-100 text-uppercase" id="short_code" placeholder="e.g. KG" required style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-4">
                            <label for="unit_type" class="form-label fw-semibold text-dark small d-block mb-1">Unit Type <span class="text-danger">*</span></label>
                            <select name="unit_type" id="unit_type" class="form-select form-select-sm px-3 py-2 w-100" required style="border-radius: 6px; border: 1px solid #cbd5e1;">
                                <option value="Weight">Weight</option>
                                <option value="Volume">Volume</option>
                                <option value="Quantity">Quantity</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="base_unit" class="form-label fw-semibold text-dark small d-block mb-1">Base Unit</label>
                            <select name="base_unit" id="base_unit" class="form-select form-select-sm px-3 py-2 w-100" style="border-radius: 6px; border: 1px solid #cbd5e1;">
                                <option value="">-- None (Primary Base) --</option>
                                @foreach($baseUnits as $bu)
                                    <option value="{{ $bu->id }}">{{ $bu->name }} ({{ $bu->short_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="conversion_factor" class="form-label fw-semibold text-dark small d-block mb-1">Conversion Factor</label>
                            <input type="number" step="0.0001" name="conversion_factor" class="form-control form-control-sm px-3 py-2 w-100" id="conversion_factor" placeholder="e.g. 0.001" value="1" style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-12 d-flex align-items-center">
                            <label class="form-label fw-semibold text-dark small mb-0 me-3">Status</label>
                            <input type="hidden" name="status" value="0">
                            <label class="switch mb-0">
                                <input type="checkbox" name="status" id="status" value="1" checked>
                                <span class="slider"></span>
                            </label>
                            <span class="ms-2 small text-muted" id="statusLabel">Active</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-2 border-top">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-dismiss="modal">Close</button>
                    @canany(['units.add', 'units.edit'])
                        <button type="submit" class="btn btn-sm btn-primary px-3 save-btn">
                            Save Unit
                        </button>
                    @endcanany
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/js/mycode.js') }}"></script>
<script>
    $('#status').on('change', function() {
        if($(this).is(':checked')) {
            $('#statusLabel').text('Active').removeClass('text-danger').addClass('text-success');
        } else {
            $('#statusLabel').text('Inactive').removeClass('text-success').addClass('text-danger');
        }
    });

    $('#base_unit').on('change', function() {
        if($(this).val() === "") {
            $('#conversion_factor').val("1").prop('readonly', true);
        } else {
            $('#conversion_factor').prop('readonly', false);
        }
    });

    $(document).on('submit', '.myform', function(e) {
        e.preventDefault();
        var formdata = new FormData(this);
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        $(this).find(':submit').attr('disabled', true);
        myAjax(url, formdata, method);
    });

    $(document).on('click', '.edit-btn', function() {
        var item = $(this).closest(".unit-row-item");
        var id = item.find(".id").text().replace('#', '').trim();
        var name = item.find(".name").text().trim();
        var code = item.find(".code").text().trim();
        var type = item.find(".type").text().trim();
        var base_id = item.find(".base").data('baseid');
        var factor = item.find(".factor").text().trim();
        var status = item.find(".status").data('status');

        $('#id').val(id);
        $('#name').val(name);
        $('#short_code').val(code);
        $('#unit_type').val(type);
        $('#base_unit').val(base_id).trigger('change');
        $('#conversion_factor').val(factor);
        
        if (status == 1 || status === undefined) {
            $('#status').prop('checked', true).trigger('change');
        } else {
            $('#status').prop('checked', false).trigger('change');
        }

        $('#modalTitleText').text('Edit Unit');
        $("#exampleModal").modal("show");
    });

    $('#reset').on('click', function() {
        $('#id').val('');
        $('#name').val('');
        $('#short_code').val('');
        $('#unit_type').val('Weight');
        $('#base_unit').val('').trigger('change');
        $('#conversion_factor').val('1');
        $('#status').prop('checked', true).trigger('change');
        $('#modalTitleText').text('Add Unit');
    });

    $(document).ready(function() {
        $('#base_unit').trigger('change');

        if($('#default-datatable').length) {
            $('#default-datatable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "order": [[0, 'desc']],
                "language": {
                    "search": "Search Units:",
                    "lengthMenu": "Show _MENU_ entries"
                }
            });
        }
    });
</script>
@endsection
