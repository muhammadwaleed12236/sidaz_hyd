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

    .rm-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    /* Page Header */
    .rm-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .rm-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .rm-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    /* Stat Cards */
    .rm-stat-card {
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
    .rm-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #fff7ed; /* Orange for RM */
        color: #ea580c; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .rm-stat-icon.active {
        background: #dcfce7;
        color: #16a34a;
    }
    .rm-stat-val {
        font-weight: 800;
        font-size: 1.25rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .rm-stat-lbl {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Primary Gradient Button */
    .btn-rm-primary {
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
        color: #ffffff !important;
        border: none;
        padding: 10px 20px;
        font-size: 0.86rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.22);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-rm-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(234, 88, 12, 0.35);
    }

    /* Card & Table */
    .rm-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .rm-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .rm-table-wrap {
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
    .rm-id-badge {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-family: monospace;
    }
    .rm-code-badge {
        background: #fff7ed;
        color: #ea580c;
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
    .unit-badge {
        background: #fdf4ff;
        color: #c026d3;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .dept-badge {
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
        border-color: #ea580c;
        box-shadow: 0 0 0 0.2rem rgba(234, 88, 12, 0.15);
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

<div class="rm-page container-fluid px-3 px-md-4 pt-3">
    
    {{-- Header Row --}}
    <div class="rm-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="rm-title"><i class="fas fa-box-open text-primary me-2" style="color: #ea580c !important;"></i>Raw Material Management</h3>
            <div class="rm-sub">Manage raw materials, ingredients, and components for production</div>
        </div>
        @can('raw_materials.create')
            <button type="button" class="btn-rm-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                <i class="fas fa-plus"></i> Add Material
            </button>
        @endcan
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="rm-stat-card">
                <div class="rm-stat-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="rm-stat-val">{{ count($rawMaterials) }}</div>
                    <div class="rm-stat-lbl">Total Materials</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="rm-stat-card">
                <div class="rm-stat-icon active"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="rm-stat-val">{{ $rawMaterials->where('status', 1)->count() }}</div>
                    <div class="rm-stat-lbl">Active Materials</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="rm-card">
        <div class="rm-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Raw Materials</div>
            <div class="text-muted small">Showing {{ count($rawMaterials) }} entries</div>
        </div>

        {{-- Desktop Table View --}}
        <div class="rm-table-wrap">
            <table id="default-datatable" class="table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px;">Id</th>
                        <th class="text-start">Name</th>
                        <th class="text-start">Code/SKU</th>
                        <th class="text-start">Department</th>
                        <th class="text-start">Type</th>
                        <th class="text-start">Unit</th>
                        <th class="text-end">Price</th>
                        <th class="text-center">Status</th>
                        <th class="d-none">Min Stock</th>
                        <th class="d-none">Reorder Level</th>
                        <th class="d-none">Description</th>
                        <th class="text-end pe-4" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rawMaterials as $rm)
                        <tr>
                            <td class="text-center id"><span class="rm-id-badge">#{{ $rm->id }}</span></td>
                            <td class="text-start name fw-semibold text-dark">{{ $rm->name }}</td>
                            <td class="text-start code"><span class="rm-code-badge">{{ $rm->code }}</span></td>
                            
                            <td class="text-start department" data-deptid="{{ $rm->department_id }}">
                                @if($rm->department)
                                    <span class="dept-badge">{{ $rm->department->name }}</span>
                                @endif
                            </td>

                            <td class="text-start type"><span class="type-badge">{{ $rm->type }}</span></td>
                            
                            <td class="text-start unit" data-unitid="{{ $rm->unit_id }}">
                                @if($rm->unit)
                                    <span class="unit-badge">{{ $rm->unit->short_code }}</span>
                                @endif
                            </td>

                            <td class="text-end fw-bold text-success price">{{ number_format((float)$rm->price, 2) }}</td>

                            <td class="text-center status" data-status="{{ $rm->status }}">
                                @if($rm->status)
                                    <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> Inactive</span>
                                @endif
                            </td>
                            <td class="d-none min_stock">{{ $rm->min_stock }}</td>
                            <td class="d-none reorder_level">{{ $rm->reorder_level }}</td>
                            <td class="d-none description">{{ $rm->description }}</td>

                            <td class="text-end pe-4">
                                @include('admin_panel.partials.action_buttons', [
                                    'editRoute' => route('raw_materials.store'),
                                    'deleteRoute' => route('raw_materials.delete', $rm->id),
                                    'editIsLink' => false,
                                    'permissions' => [
                                        'edit' => 'raw_materials.edit',
                                        'delete' => 'raw_materials.delete',
                                    ],
                                    'deleteMsg' => 'Are you sure you want to delete this raw material?',
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add/Edit Raw Material Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-light px-4 py-3 border-bottom">
                <h5 class="modal-title fw-semibold text-dark" id="exampleModalLabel">
                    <span id="modalTitleText">Add Raw Material</span>
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="myform" action="{{ route('raw_materials.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label text-dark small fw-medium">Material Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control px-3 py-2" id="name" placeholder="e.g. Paracetamol API" required style="border-radius: 8px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label text-dark small fw-medium">Material Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control px-3 py-2 text-uppercase" id="code" placeholder="RM-001" required style="border-radius: 8px; border: 1.5px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="department_id" class="form-label text-dark small fw-medium">Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select px-3 py-2" required style="border-radius: 8px; border: 1.5px solid #cbd5e1;">
                                <option value="">-- Select --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label text-dark small fw-medium">Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select px-3 py-2" required style="border-radius: 8px; border: 1.5px solid #cbd5e1;">
                                <option value="Ingredient">Ingredient</option>
                                <option value="Chemical">Chemical</option>
                                <option value="Powder">Powder</option>
                                <option value="Liquid">Liquid</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="unit_id" class="form-label text-dark small fw-medium">Base Unit <span class="text-danger">*</span></label>
                            <select name="unit_id" id="unit_id" class="form-select px-3 py-2" required style="border-radius: 8px; border: 1.5px solid #cbd5e1;">
                                <option value="">-- Select --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->short_code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label text-dark small fw-medium">Price (Per Unit) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control px-3 py-2" id="price" placeholder="0.00" required style="border-radius: 8px; border: 1.5px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="min_stock" class="form-label text-dark small fw-medium">Min Stock Level</label>
                            <input type="number" step="0.0001" name="min_stock" class="form-control px-3 py-2" id="min_stock" placeholder="0.00" style="border-radius: 8px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="reorder_level" class="form-label text-dark small fw-medium">Reorder Alert</label>
                            <input type="number" step="0.0001" name="reorder_level" class="form-control px-3 py-2" id="reorder_level" placeholder="0.00" style="border-radius: 8px; border: 1.5px solid #cbd5e1;" />
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label text-dark small fw-medium">Description</label>
                            <textarea name="description" class="form-control px-3 py-2" id="description" rows="2" placeholder="Optional details..." style="border-radius: 8px; border: 1.5px solid #cbd5e1;"></textarea>
                        </div>

                        <div class="col-md-12 mb-1 d-flex align-items-center">
                            <label class="form-label text-dark small fw-medium mb-0 me-3">Status</label>
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
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Close</button>
                    @canany(['raw_materials.create', 'raw_materials.edit'])
                        <button type="submit" class="btn btn-primary px-4 save-btn">
                            Save Material
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
        var dept_id = tr.find(".department").data('deptid');
        var type = tr.find(".type").text().trim();
        var unit_id = tr.find(".unit").data('unitid');
        var price = tr.find(".price").text().replace(/,/g, '').trim();
        var min_stock = tr.find(".min_stock").text().trim();
        var reorder_level = tr.find(".reorder_level").text().trim();
        var desc = tr.find(".description").text().trim();
        var status = tr.find(".status").data('status');

        $('#id').val(id);
        $('#name').val(name);
        $('#code').val(code);
        $('#department_id').val(dept_id).trigger('change');
        $('#type').val(type).trigger('change');
        $('#unit_id').val(unit_id).trigger('change');
        $('#price').val(price);
        $('#min_stock').val(min_stock);
        $('#reorder_level').val(reorder_level);
        $('#description').val(desc);
        
        if (status == 1 || status === undefined) {
            $('#status').prop('checked', true).trigger('change');
        } else {
            $('#status').prop('checked', false).trigger('change');
        }

        $('#modalTitleText').text('Edit Raw Material');
        $("#exampleModal").modal("show");
    });

    $('#reset').on('click', function() {
        $('#id').val('');
        $('#name').val('');
        $('#code').val('');
        $('#department_id').val('').trigger('change');
        $('#type').val('Ingredient').trigger('change');
        $('#unit_id').val('').trigger('change');
        $('#price').val('');
        $('#min_stock').val('');
        $('#reorder_level').val('');
        $('#description').val('');
        $('#status').prop('checked', true).trigger('change');
        $('#modalTitleText').text('Add Raw Material');
    });

    $(document).ready(function() {
        $('#default-datatable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, 'desc']],
            "language": {
                "search": "Search Materials:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>
@endsection
