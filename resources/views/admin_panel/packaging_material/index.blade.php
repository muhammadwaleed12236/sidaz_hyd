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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .pm-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    /* Page Header */
    .pm-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .pm-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .pm-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    /* Stat Cards */
    .pm-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }
    .pm-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #f0fdfa; /* Teal for PM */
        color: #0d9488; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .pm-stat-icon.active {
        background: #dcfce7;
        color: #16a34a;
    }
    .pm-stat-val {
        font-weight: 800;
        font-size: 1.25rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .pm-stat-lbl {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Primary Gradient Button */
    .btn-pm-primary {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        color: #ffffff !important;
        border: none;
        padding: 10px 20px;
        font-size: 0.86rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.22);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-pm-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(13, 148, 136, 0.35);
    }

    /* Card & Table */
    .pm-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .pm-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pm-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    #default-datatable {
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    #default-datatable thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 20px;
    }
    #default-datatable tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    #default-datatable tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Badges */
    .pm-id-badge {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-family: monospace;
    }
    .pm-code-badge {
        background: #f0fdfa;
        color: #0d9488;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
        letter-spacing: 0.5px;
    }
    .type-badge {
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .variant-badge {
        background: #fdf4ff;
        color: #c026d3;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .unit-badge {
        background: #e0f2fe;
        color: #0284c7;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active {
        background: #dcfce7;
        color: #166534;
    }
    .status-inactive {
        background: #fef2f2;
        color: #991b1b;
    }

    /* Form UI */
    .form-control:focus, .form-select:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.15);
    }
    
    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #10b981;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }

</style>

<div class="pm-page container-fluid px-3 px-md-4 pt-3">
    
    {{-- Header Row --}}
    <div class="pm-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="pm-title"><i class="fas fa-boxes text-primary me-2" style="color: #0d9488 !important;"></i>Packaging Material</h3>
            <div class="pm-sub">Manage packaging inventory like bottles, boxes, labels, and caps</div>
        </div>
        @can('packaging_materials.create')
            <button type="button" class="btn-pm-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                <i class="fas fa-plus"></i> Add Packaging
            </button>
        @endcan
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="pm-stat-card">
                <div class="pm-stat-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="pm-stat-val">{{ count($packagingMaterials) }}</div>
                    <div class="pm-stat-lbl">Total Packaging</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="pm-stat-card">
                <div class="pm-stat-icon active"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="pm-stat-val">{{ $packagingMaterials->where('status', 1)->count() }}</div>
                    <div class="pm-stat-lbl">Active Packaging</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="pm-card">
        <div class="pm-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Packaging Materials</div>
            <div class="text-muted small">Showing {{ count($packagingMaterials) }} entries</div>
        </div>

        {{-- Desktop Table View --}}
        <div class="pm-table-wrap">
            <table id="default-datatable" class="table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px;">Id</th>
                        <th class="text-start">Name</th>
                        <th class="text-start">Code/SKU</th>
                        <th class="text-start">Type & Variant</th>
                        <th class="text-start">Capacity</th>
                        <th class="text-start">Unit (Stock)</th>
                        <th class="text-center">Status</th>
                        <th class="d-none">Department</th>
                        <th class="d-none">Min Stock</th>
                        <th class="d-none">Description</th>
                        <th class="text-end pe-4" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packagingMaterials as $pm)
                        <tr>
                            <td class="text-center id"><span class="pm-id-badge">#{{ $pm->id }}</span></td>
                            <td class="text-start name fw-semibold text-dark">{{ $pm->name }}</td>
                            <td class="text-start code"><span class="pm-code-badge">{{ $pm->code }}</span></td>
                            
                            <td class="text-start type_variant">
                                <span class="type-badge type-val" data-val="{{ $pm->packaging_type }}">{{ $pm->packaging_type }}</span>
                                @if($pm->variant)
                                    <span class="variant-badge variant-val" data-val="{{ $pm->variant }}">{{ $pm->variant }}</span>
                                @else
                                    <span class="variant-val d-none" data-val=""></span>
                                @endif
                            </td>

                            <td class="text-start capacity-data" data-cap="{{ $pm->capacity }}" data-capunit="{{ $pm->capacity_unit_id }}">
                                @if($pm->capacity)
                                    <span class="fw-semibold text-dark">{{ $pm->capacity }}</span> 
                                    <span class="text-muted small">{{ $pm->capacityUnit ? $pm->capacityUnit->short_code : '' }}</span>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            
                            <td class="text-start unit" data-unitid="{{ $pm->unit_id }}">
                                @if($pm->unit)
                                    <span class="unit-badge">{{ $pm->unit->short_code }}</span>
                                @endif
                            </td>

                            <td class="text-center status" data-status="{{ $pm->status }}">
                                @if($pm->status)
                                    <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Inactive</span>
                                @endif
                            </td>

                            <td class="d-none department" data-deptid="{{ $pm->department_id }}"></td>
                            <td class="d-none min_stock">{{ $pm->min_stock }}</td>
                            <td class="d-none description">{{ $pm->description }}</td>

                            <td class="text-end pe-4">
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
        </div>
    </div>
</div>

{{-- Add/Edit Packaging Material Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-white px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="exampleModalLabel"><i class="fas fa-boxes text-primary me-2" style="color: #0d9488 !important;"></i><span id="modalTitleText">Add Packaging</span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="myform" action="{{ route('packaging_materials.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold text-dark small">Packaging Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control px-3 py-2" id="name" placeholder="e.g. Syrup Bottle 100ML" required style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label fw-semibold text-dark small">Code / SKU <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control px-3 py-2" id="code" placeholder="e.g. PM-001" required style="border-radius: 10px; border: 1.5px solid #cbd5e1; text-transform: uppercase;" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="packaging_type" class="form-label fw-semibold text-dark small">Packaging Type <span class="text-danger">*</span></label>
                            <select name="packaging_type" id="packaging_type" class="form-control px-3 py-2" required style="border-radius: 10px; border: 1.5px solid #cbd5e1;">
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
                        
                        <div class="col-md-4 mb-3">
                            <label for="variant" class="form-label fw-semibold text-dark small">Variant (Optional)</label>
                            <input type="text" name="variant" class="form-control px-3 py-2" id="variant" placeholder="e.g. Glass, Plastic" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="department_id" class="form-label fw-semibold text-dark small d-block mb-1">Department</label>
                            <select name="department_id" id="department_id" class="form-control px-3 py-2 w-100" style="border-radius: 10px; border: 1.5px solid #cbd5e1;">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="unit_id" class="form-label fw-semibold text-dark small">Stock Unit <span class="text-danger">*</span></label>
                            <select name="unit_id" id="unit_id" class="form-control px-3 py-2" required style="border-radius: 10px; border: 1.5px solid #cbd5e1;">
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->short_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="capacity" class="form-label fw-semibold text-dark small">Capacity</label>
                            <input type="number" step="0.0001" name="capacity" class="form-control px-3 py-2" id="capacity" placeholder="e.g. 100" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="capacity_unit_id" class="form-label fw-semibold text-dark small">Capacity Unit</label>
                            <select name="capacity_unit_id" id="capacity_unit_id" class="form-control px-3 py-2" style="border-radius: 10px; border: 1.5px solid #cbd5e1;">
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->short_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="min_stock" class="form-label fw-semibold text-dark small">Min Stock Level</label>
                            <input type="number" step="0.0001" name="min_stock" class="form-control px-3 py-2" id="min_stock" placeholder="0.00" style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-semibold text-dark small">Description</label>
                            <textarea name="description" class="form-control px-3 py-2" id="description" rows="2" placeholder="Optional details..." style="border-radius: 10px; border: 1.5px solid #cbd5e1;"></textarea>
                        </div>

                        <div class="col-md-12 mb-1 d-flex align-items-center">
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
                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" data-dismiss="modal" style="border-radius: 8px;">Close</button>
                    @canany(['packaging_materials.create', 'packaging_materials.edit'])
                        <button type="submit" class="btn btn-primary px-4 fw-bold save-btn" style="border-radius: 8px; background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); border: none;">
                            <i class="fas fa-check me-1"></i> Save Packaging
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
    // Status Toggle Label
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
        var tr = $(this).closest("tr");
        var id = tr.find(".id").text().replace('#', '').trim();
        var name = tr.find(".name").text().trim();
        var code = tr.find(".code").text().trim();
        
        var type = tr.find(".type-val").data('val');
        var variant = tr.find(".variant-val").data('val');

        var dept_id = tr.find(".department").data('deptid');
        var unit_id = tr.find(".unit").data('unitid');
        
        var capacity = tr.find(".capacity-data").data('cap');
        var capacity_unit_id = tr.find(".capacity-data").data('capunit');

        var min_stock = tr.find(".min_stock").text().trim();
        var desc = tr.find(".description").text().trim();
        var status = tr.find(".status").data('status');

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
        $('#default-datatable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, 'desc']],
            "language": {
                "search": "Search Packaging:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>
@endsection
