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
    .btn-hdr-primary { background: #0d9488; color: #fff; border-color: #0d9488; box-shadow: 0 1px 3px rgba(13,148,136,0.2); }
    .btn-hdr-primary:hover { background: #0f766e; border-color: #0f766e; color: #fff; }

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
    .pm-id-badge { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; font-family: monospace; }
    .pm-code-badge { background: #f0fdfa; color: #0d9488; font-weight: 600; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; border: 1px solid #ccfbf1; }
    .type-badge { background: #eef2ff; color: #4f46e5; font-weight: 600; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; }
    .variant-badge { background: #fef3c7; color: #b45309; font-weight: 600; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; }
    .unit-badge { background: #fdf4ff; color: #c026d3; font-weight: 600; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; }
    
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
    .mobile-pm-cards { display: none; padding: 10px; }
    @media (max-width: 768px) {
        .erp-page { padding: 8px 0; }
        .erp-card-header { flex-direction: column; align-items: flex-start; gap: 8px; padding: 10px 12px; }
        .erp-hdr-actions { width: 100%; }
        .btn-hdr { width: 100%; justify-content: center; height: 34px; font-size: 0.8rem; }
        .erp-table-wrap { display: none !important; }
        .mobile-pm-cards { display: flex; flex-direction: column; gap: 8px; }
    }

    .pm-mcard {
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: 8px;
        padding: 10px 12px;
        box-shadow: 0 1px 3px rgba(15,23,42,0.03);
    }
    .pm-mcard-hd { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .pm-mcard-body {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .pm-mcard-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 6px !important;
        margin-top: 8px;
        width: 100%;
    }
    .pm-mcard-actions .btn-act { width: 100% !important; justify-content: center !important; height: 32px !important; font-size: 0.76rem !important; border-radius: 6px !important; }
</style>

<div class="erp-page">
    <div class="container-fluid px-2">
        <div class="erp-card">
            
            {{-- Header Bar --}}
            <div class="erp-card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h1 class="page-head-title"><i class="fas fa-boxes me-1" style="color: #0d9488;"></i> Packaging Material</h1>
                    <div class="head-kpis">
                        <span class="kpi-pill">Total: <strong>{{ count($packagingMaterials) }}</strong></span>
                        <span class="kpi-pill success">Active: <strong>{{ $packagingMaterials->where('status', 1)->count() }}</strong></span>
                        <span class="kpi-pill danger">Inactive: <strong>{{ $packagingMaterials->where('status', 0)->count() }}</strong></span>
                    </div>
                </div>
                <div class="erp-hdr-actions">
                    @can('packaging_materials.create')
                        <button type="button" class="btn-hdr btn-hdr-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                            <i class="fas fa-plus"></i> Add Packaging
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
                            <th>Packaging Name</th>
                            <th>Code/SKU</th>
                            <th>Type & Variant</th>
                            <th>Capacity</th>
                            <th>Stock Unit</th>
                            <th class="text-center">Status</th>
                            <th class="d-none">Department</th>
                            <th class="d-none">Min Stock</th>
                            <th class="d-none">Description</th>
                            <th class="text-center" style="width: 130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packagingMaterials as $pm)
                            <tr class="pm-row-item">
                                <td class="text-center id"><span class="pm-id-badge">#{{ $pm->id }}</span></td>
                                <td class="name fw-semibold text-dark">{{ $pm->name }}</td>
                                <td class="code"><span class="pm-code-badge">{{ $pm->code }}</span></td>
                                
                                <td class="type_variant">
                                    <span class="type-badge type-val" data-val="{{ $pm->packaging_type }}">{{ $pm->packaging_type }}</span>
                                    @if($pm->variant)
                                        <span class="variant-badge variant-val" data-val="{{ $pm->variant }}">{{ $pm->variant }}</span>
                                    @else
                                        <span class="variant-val d-none" data-val=""></span>
                                    @endif
                                </td>

                                <td class="capacity-data" data-cap="{{ $pm->capacity }}" data-capunit="{{ $pm->capacity_unit_id }}">
                                    @if($pm->capacity)
                                        <span class="fw-semibold text-dark">{{ $pm->capacity }}</span> 
                                        <span class="text-muted small">{{ $pm->capacityUnit ? $pm->capacityUnit->short_code : '' }}</span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                
                                <td class="unit" data-unitid="{{ $pm->unit_id }}">
                                    @if($pm->unit)
                                        <span class="unit-badge">{{ $pm->unit->short_code }}</span>
                                    @endif
                                </td>

                                <td class="text-center status" data-status="{{ $pm->status }}">
                                    @if($pm->status)
                                        <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 7px;"></i> Active</span>
                                    @else
                                        <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 7px;"></i> Inactive</span>
                                    @endif
                                </td>

                                <td class="d-none department" data-deptid="{{ $pm->department_id }}"></td>
                                <td class="d-none min_stock">{{ $pm->min_stock }}</td>
                                <td class="d-none description">{{ $pm->description }}</td>

                                <td class="text-center">
                                    @include('admin_panel.partials.action_buttons', [
                                        'editRoute' => route('packaging_materials.store'),
                                        'deleteRoute' => route('packaging_materials.delete', $pm->id),
                                        'editIsLink' => false,
                                        'permissions' => [
                                            'edit' => 'packaging_materials.edit',
                                            'delete' => 'packaging_materials.delete',
                                        ],
                                        'deleteMsg' => 'Are you sure you want to delete this packaging material?',
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>{{-- /erp-table-wrap --}}

            {{-- Mobile Cards View (<768px) --}}
            <div class="mobile-pm-cards">
                @foreach ($packagingMaterials as $pm)
                    <div class="pm-mcard pm-row-item">
                        <div class="pm-mcard-hd">
                            <div>
                                <span class="pm-code-badge">{{ $pm->code }}</span>
                                <div class="fw-bold text-dark fs-6 mt-1">{{ $pm->name }}</div>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <span class="type-badge type-val" data-val="{{ $pm->packaging_type }}">{{ $pm->packaging_type }}</span>
                                    @if($pm->variant)
                                        <span class="variant-badge variant-val" data-val="{{ $pm->variant }}">{{ $pm->variant }}</span>
                                    @endif
                                    @if($pm->unit)
                                        <span class="unit-badge">{{ $pm->unit->short_code }}</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($pm->status)
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="pm-mcard-body">
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:var(--erp-muted); text-transform:uppercase;">Capacity</div>
                                <div class="fw-bold text-dark fs-6">
                                    @if($pm->capacity)
                                        {{ $pm->capacity }} {{ $pm->capacityUnit ? $pm->capacityUnit->short_code : '' }}
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="pm-id-badge">ID: #{{ $pm->id }}</span>
                            </div>
                        </div>

                        {{-- Hidden elements for JS edit modal compatibility --}}
                        <span class="d-none id">#{{ $pm->id }}</span>
                        <span class="d-none name">{{ $pm->name }}</span>
                        <span class="d-none code">{{ $pm->code }}</span>
                        <span class="d-none type-val" data-val="{{ $pm->packaging_type }}"></span>
                        <span class="d-none variant-val" data-val="{{ $pm->variant }}"></span>
                        <span class="d-none department" data-deptid="{{ $pm->department_id }}"></span>
                        <span class="d-none unit" data-unitid="{{ $pm->unit_id }}"></span>
                        <span class="d-none capacity-data" data-cap="{{ $pm->capacity }}" data-capunit="{{ $pm->capacity_unit_id }}"></span>
                        <span class="d-none min_stock">{{ $pm->min_stock }}</span>
                        <span class="d-none description">{{ $pm->description }}</span>
                        <span class="d-none status" data-status="{{ $pm->status }}"></span>

                        <div class="pm-mcard-actions">
                            @can('packaging_materials.edit')
                                <button type="button" class="btn-act btn-act-edit edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            @endcan
                            @can('packaging_materials.delete')
                                <form action="{{ route('packaging_materials.delete', $pm->id) }}" method="GET" class="d-inline w-100" onsubmit="return confirm('Are you sure you want to delete this packaging material?')">
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

{{-- Add/Edit Packaging Material Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 10px;">
            <div class="modal-header bg-light px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark fs-6" id="exampleModalLabel"><span id="modalTitleText">Add Packaging</span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="myform" action="{{ route('packaging_materials.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold text-dark small d-block mb-1">Packaging Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm px-3 py-2 w-100" id="name" placeholder="e.g. Syrup Bottle 100ML" required style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6">
                            <label for="code" class="form-label fw-semibold text-dark small d-block mb-1">Code / SKU <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control form-control-sm px-3 py-2 w-100 text-uppercase" id="code" placeholder="e.g. PM-001" required style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-4">
                            <label for="packaging_type" class="form-label fw-semibold text-dark small d-block mb-1">Packaging Type <span class="text-danger">*</span></label>
                            <select name="packaging_type" id="packaging_type" class="form-select form-select-sm px-3 py-2 w-100" required style="border-radius: 6px; border: 1px solid #cbd5e1;">
                                <option value="Bottle">Bottle</option>
                                <option value="Box">Box</option>
                                <option value="Cap">Cap</option>
                                <option value="Label">Label</option>
                                <option value="Carton">Carton</option>
                                <option value="Seal">Seal</option>
                                <option value="Wrapper">Wrapper</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="variant" class="form-label fw-semibold text-dark small d-block mb-1">Variant (Optional)</label>
                            <input type="text" name="variant" class="form-control form-control-sm px-3 py-2 w-100" id="variant" placeholder="e.g. Glass, Plastic" style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-4">
                            <label for="department_id" class="form-label fw-semibold text-dark small d-block mb-1">Department</label>
                            <select name="department_id" id="department_id" class="form-select form-select-sm px-3 py-2 w-100" style="border-radius: 6px; border: 1px solid #cbd5e1;">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="unit_id" class="form-label fw-semibold text-dark small d-block mb-1">Stock Unit <span class="text-danger">*</span></label>
                            <select name="unit_id" id="unit_id" class="form-select form-select-sm px-3 py-2 w-100" required style="border-radius: 6px; border: 1px solid #cbd5e1;">
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->short_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="capacity" class="form-label fw-semibold text-dark small d-block mb-1">Capacity</label>
                            <input type="number" step="0.0001" name="capacity" class="form-control form-control-sm px-3 py-2 w-100" id="capacity" placeholder="e.g. 100" style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                        </div>
                        
                        <div class="col-md-4">
                            <label for="capacity_unit_id" class="form-label fw-semibold text-dark small d-block mb-1">Capacity Unit</label>
                            <select name="capacity_unit_id" id="capacity_unit_id" class="form-select form-select-sm px-3 py-2 w-100" style="border-radius: 6px; border: 1px solid #cbd5e1;">
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->short_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="min_stock" class="form-label fw-semibold text-dark small d-block mb-1">Min Stock Level</label>
                            <input type="number" step="0.0001" name="min_stock" class="form-control form-control-sm px-3 py-2 w-100" id="min_stock" placeholder="0.00" style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-12">
                            <label for="description" class="form-label fw-semibold text-dark small d-block mb-1">Description</label>
                            <textarea name="description" class="form-control form-control-sm px-3 py-2 w-100" id="description" rows="2" placeholder="Optional details..." style="border-radius: 6px; border: 1px solid #cbd5e1;"></textarea>
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
                    @canany(['packaging_materials.create', 'packaging_materials.edit'])
                        <button type="submit" class="btn btn-sm btn-primary px-3 save-btn">
                            Save Packaging
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

    $(document).on('submit', '.myform', function(e) {
        e.preventDefault();
        var formdata = new FormData(this);
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        $(this).find(':submit').attr('disabled', true);
        myAjax(url, formdata, method);
    });

    $(document).on('click', '.edit-btn', function() {
        var item = $(this).closest(".pm-row-item");
        var id = item.find(".id").text().replace('#', '').trim();
        var name = item.find(".name").text().trim();
        var code = item.find(".code").text().trim();
        
        var type = item.find(".type-val").data('val');
        var variant = item.find(".variant-val").data('val');

        var dept_id = item.find(".department").data('deptid');
        var unit_id = item.find(".unit").data('unitid');
        
        var capacity = item.find(".capacity-data").data('cap');
        var capacity_unit_id = item.find(".capacity-data").data('capunit');

        var min_stock = item.find(".min_stock").text().trim();
        var desc = item.find(".description").text().trim();
        var status = item.find(".status").data('status');

        $('#id').val(id);
        $('#name').val(name);
        $('#code').val(code);
        $('#packaging_type').val(type).trigger('change');
        $('#variant').val(variant);
        $('#department_id').val(dept_id).trigger('change');
        $('#unit_id').val(unit_id).trigger('change');
        $('#capacity').val(capacity);
        $('#capacity_unit_id').val(capacity_unit_id).trigger('change');
        $('#min_stock').val(min_stock);
        $('#description').val(desc);
        
        if (status == 1 || status === undefined) {
            $('#status').prop('checked', true).trigger('change');
        } else {
            $('#status').prop('checked', false).trigger('change');
        }

        $('#modalTitleText').text('Edit Packaging Material');
        $("#exampleModal").modal("show");
    });

    $('#reset').on('click', function() {
        $('#id').val('');
        $('#name').val('');
        $('#code').val('');
        $('#packaging_type').val('Bottle').trigger('change');
        $('#variant').val('');
        $('#department_id').val('').trigger('change');
        $('#unit_id').val('').trigger('change');
        $('#capacity').val('');
        $('#capacity_unit_id').val('').trigger('change');
        $('#min_stock').val('');
        $('#description').val('');
        $('#status').prop('checked', true).trigger('change');
        $('#modalTitleText').text('Add Packaging Material');
    });

    $(document).ready(function() {
        if($('#default-datatable').length) {
            $('#default-datatable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "order": [[0, 'desc']],
                "language": {
                    "search": "Search Packaging:",
                    "lengthMenu": "Show _MENU_ entries"
                }
            });
        }
    });
</script>
@endsection
