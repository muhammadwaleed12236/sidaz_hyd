@extends('admin_panel.layout.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .sup-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    .sup-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .sup-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .sup-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    .sup-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .sup-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        font-weight: 700;
        color: #0f172a;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 6px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .table-custom thead th {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
    }
    .table-custom tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .btn-add-row {
        background: #e0e7ff;
        color: #4338ca;
        border: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    .btn-add-row:hover {
        background: #c7d2fe;
    }

    .btn-remove-row {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-remove-row:hover {
        background: #fca5a5;
    }

    .select2-container--default .select2-selection--single {
        height: 42px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
        padding-left: 14px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
</style>

<div class="sup-page container-fluid px-3 px-md-4 pt-3">
    <div class="sup-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="sup-title"><i class="fas fa-plus-circle text-primary me-2"></i>Create Formulation</h3>
            <div class="sup-sub">Define a new recipe for a finished product</div>
        </div>
        <div>
            <a href="{{ route('formulations.index') }}" class="btn btn-light border fw-bold text-dark">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('formulations.store') }}" method="POST">
        @csrf
        
        <!-- Header Section -->
        <div class="sup-card border-primary" style="border-top: 3px solid #0284c7;">
            <div class="sup-card-header bg-white pb-2 d-flex justify-content-between align-items-center">
                <span class="fs-5 text-dark"><i class="fas fa-file-invoice text-primary me-2"></i>Batch Manufacturing Record (BMR)</span>
                <span class="badge bg-light text-dark border px-3 py-2 fs-6">Doc: <input type="text" name="doc_no" class="border-0 bg-transparent text-end fw-bold text-dark" style="width: 120px; outline: none;" placeholder="DOC-001"></span>
            </div>
            <div class="p-4" style="background-color: #f8fafc;">
                
                <h6 class="fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Product Details</h6>
                <div class="row g-3 mb-4 bg-white p-3 rounded shadow-sm border">
                    <div class="col-md-5">
                        <label class="form-label text-dark fw-bold small">Product Name <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select select2" required>
                            <option value="">Select Product...</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>{{ $p->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-dark fw-bold small">Batch No <span class="text-danger">*</span></label>
                        <input type="text" name="batch_no" class="form-control fw-bold text-primary" required placeholder="e.g. SP26023B">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-dark fw-bold small">Brand / Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g. Sidaz Pharma">
                    </div>
                </div>

                <h6 class="fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Batch Metrics & Size</h6>
                <div class="row g-3 mb-4 bg-white p-3 rounded shadow-sm border">
                    <div class="col-md-3">
                        <label class="form-label text-dark fw-bold small">Qty of Dropper/Bottle <span class="text-danger">*</span></label>
                        <input type="number" id="qty_of_dropper" name="qty_of_dropper" class="form-control fw-bold" required placeholder="1000">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-dark fw-bold small">Weight Per Bottle</label>
                        <div class="input-group">
                            <input type="number" step="0.01" id="weight_per_bottle" name="weight_per_bottle" class="form-control" placeholder="30">
                            <span class="input-group-text bg-light">ML</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-success fw-bold small">Batch Size (Auto) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="batch_size" name="batch_size" class="form-control border-success text-success fw-bold" value="{{ old('batch_size') }}" required placeholder="30">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-dark fw-bold small">Batch Unit <span class="text-danger">*</span></label>
                        <select name="batch_unit_id" class="form-select select2" required>
                            <option value="">Select Unit...</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ old('batch_unit_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->short_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h6 class="fw-bold text-secondary text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.05em;">Dates & Status</h6>
                <div class="row g-3 bg-white p-3 rounded shadow-sm border">
                    <div class="col-md-3">
                        <label class="form-label text-dark fw-bold small">BMR No</label>
                        <input type="text" name="bmr_no" class="form-control" placeholder="SP060200">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-dark fw-bold small">Issue Date</label>
                        <input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-dark fw-bold small">Mfg Date</label>
                        <input type="date" name="mfg_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-dark fw-bold small">Exp Date</label>
                        <input type="date" name="exp_date" class="form-control">
                    </div>

                    <div class="col-md-3 mt-4">
                        <label class="form-label text-dark fw-bold small">Formulation Code</label>
                        <input type="text" name="formulation_code" class="form-control bg-light" value="{{ old('formulation_code', $nextCode) }}" required readonly>
                    </div>
                    <div class="col-md-3 mt-4">
                        <label class="form-label text-dark fw-bold small">Department</label>
                        <select name="department_id" class="form-select select2">
                            <option value="">Select Dept...</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" {{ old('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mt-4">
                        <label class="form-label text-dark fw-bold small">Version</label>
                        <input type="text" name="version" class="form-control" value="{{ old('version', '1.0') }}" required>
                    </div>
                    <div class="col-md-3 mt-4">
                        <label class="form-label text-dark fw-bold small">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (In Production)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <!-- Raw Materials Section -->
        <div class="sup-card">
            <div class="sup-card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-vial text-info me-2"></i>Raw Materials</span>
                <button type="button" class="btn-add-row" onclick="addRawMaterialRow()"><i class="fas fa-plus me-1"></i>Add Raw Material</button>
            </div>
            <div class="p-0 table-responsive">
                <table class="table table-custom mb-0" id="rawMaterialsTable">
                    <thead>
                        <tr>
                            <th style="width: 30%">Raw Material *</th>
                            <th style="width: 15%">Quantity *</th>
                            <th style="width: 12%">Unit *</th>
                            <th style="width: 12%">Cost/Rate</th>
                            <th style="width: 12%">Waste %</th>
                            <th>Notes</th>
                            <th style="width: 50px"></th>
                        </tr>
                    </thead>
                    <tbody id="rawMaterialBody">
                        <!-- Rows will be added here via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Packaging Materials Section -->
        <div class="sup-card">
            <div class="sup-card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-box-open text-warning me-2"></i>Packaging Materials</span>
                <button type="button" class="btn-add-row" onclick="addPackagingRow()"><i class="fas fa-plus me-1"></i>Add Packaging</button>
            </div>
            <div class="p-0 table-responsive">
                <table class="table table-custom mb-0" id="packagingTable">
                    <thead>
                        <tr>
                            <th style="width: 45%">Packaging Material *</th>
                            <th style="width: 20%">Quantity (PCS) *</th>
                            <th>Notes</th>
                            <th style="width: 50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be added here via JS -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Notes & Submit -->
        <div class="sup-card">
            <div class="p-4">
                <label class="form-label">Formulation Notes / Instructions</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Enter any manufacturing instructions...">{{ old('notes') }}</textarea>
                
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold" style="background: #2563eb; border-radius: 8px;">
                        <i class="fas fa-save me-2"></i> Save Formulation
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

<!-- Templates for JS -->
<table style="display: none;">
    <tbody id="rawMaterialTemplate">
        <tr>
            <td>
                <select name="raw_material_id[]" class="form-select dynamic-select2 rm-select" required>
                    <option value="">Select Raw Material...</option>
                    @foreach($rawMaterials as $rm)
                        <option value="{{ $rm->id }}" data-unit="{{ $rm->unit_id }}" data-price="{{ $rm->price }}">{{ $rm->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" step="0.0001" name="rm_quantity[]" class="form-control" required placeholder="Qty">
            </td>
            <td>
                <select name="rm_unit_id[]" class="form-select dynamic-select2 rm-unit" required>
                    <option value="">Unit...</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}">{{ $u->short_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control rm-price" readonly placeholder="0.00" style="background-color: #f1f5f9; color: #16a34a; font-weight: bold;">
            </td>
            <td>
                <input type="number" step="0.01" name="rm_waste_percent[]" class="form-control" value="0" placeholder="Waste %">
            </td>
            <td>
                <input type="text" name="rm_notes[]" class="form-control" placeholder="Notes">
            </td>
            <td class="text-center">
                <button type="button" class="btn-remove-row" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
            </td>
        </tr>
    </tbody>
    
    <tbody id="packagingTemplate">
        <tr>
            <td>
                <select name="packaging_material_id[]" class="form-select dynamic-select2" required>
                    <option value="">Select Packaging...</option>
                    @foreach($packagingMaterials as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" step="0.0001" name="pm_quantity[]" class="form-control" required placeholder="Qty">
            </td>
            <td>
                <input type="text" name="pm_notes[]" class="form-control" placeholder="Notes">
            </td>
            <td class="text-center">
                <button type="button" class="btn-remove-row" onclick="removeRow(this)"><i class="fas fa-times"></i></button>
            </td>
        </tr>
    </tbody>
</table>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
        
        // Add one initial row for each
        addRawMaterialRow();
        addPackagingRow();
        
        // Auto-select unit & price when raw material is chosen
        $(document).on('change', '.rm-select', function() {
            let selected = $(this).find(':selected');
            let unitId = selected.data('unit');
            let price = selected.data('price');
            let tr = $(this).closest('tr');
            
            if(unitId) {
                tr.find('.rm-unit').val(unitId).trigger('change');
            }
            if(price !== undefined) {
                tr.find('.rm-price').val(parseFloat(price).toFixed(2));
            } else {
                tr.find('.rm-price').val('0.00');
            }
        });

        // Calculate Batch Size automatically
        function calculateBatchSize() {
            let qty = parseFloat($('#qty_of_dropper').val()) || 0;
            let weight = parseFloat($('#weight_per_bottle').val()) || 0;
            if(qty > 0 && weight > 0) {
                // formula: (Qty * Weight) / 1000 = Liters/KGs
                let batchSize = (qty * weight) / 1000;
                $('#batch_size').val(batchSize.toFixed(2));
            }
        }

        $('#qty_of_dropper, #weight_per_bottle').on('input', calculateBatchSize);
    });

    function addRawMaterialRow() {
        let template = $('#rawMaterialTemplate').html();
        $('#rawMaterialBody').append(template);
        initDynamicSelect2();
    }

    function addPackagingRow() {
        let template = $('#packagingTemplate').html();
        $('#packagingTable tbody').append(template);
        initDynamicSelect2();
    }

    function removeRow(btn) {
        $(btn).closest('tr').remove();
    }

    function initDynamicSelect2() {
        $('.dynamic-select2').not('.select2-hidden-accessible').select2({ width: '100%' });
    }
</script>
@endsection
