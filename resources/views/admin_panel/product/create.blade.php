@extends('admin_panel.layout.app')

@section('content')
    <link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius-md: 10px;
            --radius-lg: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
        }

        .wizard-page-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 20px 15px;
        }

        /* --- Wizard Navigation Bar --- */
        .wizard-steps-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 16px 28px;
            margin-bottom: 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        }

        .wizard-step-item {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            user-select: none;
            opacity: 0.6;
            transition: all 0.25s ease;
        }

        .wizard-step-item.active {
            opacity: 1;
        }

        .wizard-step-item.completed {
            opacity: 0.9;
        }

        .step-icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #f1f5f9;
            color: #64748b;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            border: 2px solid transparent;
        }

        .wizard-step-item.active .step-icon-circle {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
        }

        .wizard-step-item.completed .step-icon-circle {
            background: #dcfce7;
            color: #16a34a;
            border-color: #86efac;
        }

        .step-label-box {
            display: flex;
            flex-direction: column;
        }

        .step-badge-text {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
        }

        .wizard-step-item.active .step-badge-text {
            color: #4f46e5;
        }

        .step-name-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
        }

        .wizard-step-divider {
            flex: 1;
            height: 2px;
            background: #e2e8f0;
            margin: 0 20px;
            border-radius: 2px;
        }

        .wizard-step-divider.active {
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
        }

        /* --- Cards & Section --- */
        .section-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header-pro {
            padding: 14px 22px;
            border-bottom: 1px solid var(--border-color);
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title-pro {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .card-body-pro {
            padding: 24px;
        }

        .form-label-pro {
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 5px;
            letter-spacing: 0.03em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-control-pro,
        .form-select-pro {
            display: block;
            width: 100%;
            padding: 9px 13px;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-main);
            background-color: #f8fafc;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .form-control-pro:focus,
        .form-select-pro:focus {
            border-color: var(--primary);
            background-color: #ffffff;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        /* Composition Item Rows */
        .comp-item-row {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .comp-item-row:hover {
            background: #ffffff;
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        /* Image Upload Box */
        .img-uploader-box {
            border: 2px dashed #cbd5e1;
            border-radius: var(--radius-md);
            background: #f8fafc;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .img-uploader-box:hover {
            border-color: #6366f1;
            background: #eef2ff;
        }

        /* Select2 Styling Inside Wizard */
        .select2-container--default .select2-selection--single {
            border: 1.5px solid var(--border-color) !important;
            border-radius: var(--radius-md) !important;
            height: 42px !important;
            padding: 6px 10px !important;
            background-color: #f8fafc !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text-main) !important;
            line-height: 28px !important;
            font-size: 0.88rem !important;
            font-weight: 500 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }

        .select2-dropdown {
            border: 1.5px solid #6366f1 !important;
            border-radius: 10px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            z-index: 1065 !important;
        }

        /* Wizard Footer Controls */
        .wizard-footer-actions {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 14px rgba(0,0,0,0.03);
        }

        /* Step Panels */
        .wizard-step-panel {
            display: none;
        }

        .wizard-step-panel.active {
            display: block;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container wizard-page-container">

                <!-- Page Banner Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.45rem;">
                            <i class="fa fa-boxes text-primary me-2"></i> Create Product Profile & Composition
                        </h2>
                        <p class="text-muted small mb-0">Follow the 3-step wizard to create a product and its raw material composition recipe (BOM)</p>
                    </div>
                    <a href="{{ route('product') }}" class="btn btn-light btn-sm font-weight-bold px-3 py-2 border" style="border-radius: 9px;">
                        <i class="fa fa-arrow-left me-1"></i> Back to Products
                    </a>
                </div>

                <!-- Wizard Progress Navigation -->
                <div class="wizard-steps-bar">
                    <div class="wizard-step-item active" id="step_tab_1" onclick="switchStep(1)">
                        <div class="step-icon-circle">1</div>
                        <div class="step-label-box">
                            <span class="step-badge-text">STEP 1</span>
                            <span class="step-name-text">Product Profile</span>
                        </div>
                    </div>

                    <div class="wizard-step-divider" id="step_line_1"></div>

                    <div class="wizard-step-item" id="step_tab_2" onclick="switchStep(2)">
                        <div class="step-icon-circle">2</div>
                        <div class="step-label-box">
                            <span class="step-badge-text">STEP 2</span>
                            <span class="step-name-text">Raw Materials Composition</span>
                        </div>
                    </div>

                    <div class="wizard-step-divider" id="step_line_2"></div>

                    <div class="wizard-step-item" id="step_tab_3" onclick="switchStep(3)">
                        <div class="step-icon-circle">3</div>
                        <div class="step-label-box">
                            <span class="step-badge-text">STEP 3</span>
                            <span class="step-name-text">Review & Save</span>
                        </div>
                    </div>
                </div>

                <!-- MAIN FORM CONTAINER -->
                <form id="productWizardForm" action="{{ route('store-product') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" name="size_mode" value="by_pieces">

                    <!-- ============================================================== -->
                    <!-- STEP 1: PRODUCT PROFILE CREATION -->
                    <!-- ============================================================== -->
                    <div class="wizard-step-panel active" id="wizard_step_1">
                        <div class="row">
                            <!-- Left Column: Core Identity Specs -->
                            <div class="col-lg-8">
                                <div class="section-card">
                                    <div class="card-header-pro">
                                        <h5 class="card-title-pro text-primary">
                                            <i class="fa fa-box-open"></i> 1. Product Identity & Specifications
                                        </h5>
                                        <span class="badge bg-primary-subtle text-primary font-weight-bold px-2 py-1">Basic Profile</span>
                                    </div>
                                    <div class="card-body-pro">
                                        <div class="row g-3">
                                            <!-- Product Type -->
                                            <div class="col-md-4">
                                                <label class="form-label-pro">Product Type <span class="text-danger">*</span></label>
                                                <select name="product_type" id="product_type" class="form-select-pro" required onchange="toggleProductType()">
                                                    <option value="Finished Good">Finished Good</option>
                                                    <option value="Raw Material">Raw Material</option>
                                                </select>
                                            </div>

                                            <!-- Product Name -->
                                            <div class="col-md-8">
                                                <label class="form-label-pro">
                                                    Product Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="product_name" id="product_name" class="form-control-pro font-weight-bold" required
                                                    placeholder="e.g. Paracetamol Syrup 120ml, Amoxicillin 250mg Suspension">
                                            </div>

                                            <!-- Base Unit of Measure -->
                                            <div class="col-md-4">
                                                <label class="form-label-pro">
                                                    Base Unit (UOM) <span class="text-danger">*</span>
                                                </label>
                                                <select name="unit" id="product_unit" class="form-select-pro" required>
                                                    <option value="">-- Select Unit --</option>
                                                    @foreach ($units as $u)
                                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                    @endforeach
                                                    <option value="Liter">Liter (Ltr)</option>
                                                    <option value="mL">Milliliter (mL)</option>
                                                    <option value="Kg">Kilogram (Kg)</option>
                                                    <option value="Bottle">Bottle</option>
                                                    <option value="Pack">Pack</option>
                                                    <option value="Piece">Piece / Vial</option>
                                                </select>
                                            </div>

                                            <!-- Category -->
                                            <div class="col-md-4">
                                                <label class="form-label-pro">Category <span class="text-danger">*</span></label>
                                                <select name="category_id" id="category_id" class="form-select-pro" required>
                                                    <option value="">-- Select Category --</option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Subcategory -->
                                            <div class="col-md-4">
                                                <label class="form-label-pro">Subcategory</label>
                                                <select name="sub_category_id" id="sub_category_id" class="form-select-pro">
                                                    <option value="">-- Select Subcategory --</option>
                                                </select>
                                            </div>

                                            <!-- Brand -->
                                            <div class="col-md-4">
                                                <label class="form-label-pro">Brand / Line</label>
                                                <select name="brand_id" id="brand_id" class="form-select-pro">
                                                    <option value="">-- Select Brand --</option>
                                                    @foreach ($brands as $b)
                                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Department -->
                                            <div class="col-md-6">
                                                <label class="form-label-pro">Manufacturing Department</label>
                                                <select name="department_id" id="department_id" class="form-select-pro">
                                                    <option value="">-- Select Department --</option>
                                                    @foreach ($departments as $dept)
                                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Batch Unit Size -->
                                            <div class="col-md-6">
                                                <label class="form-label-pro">Standard Batch Size (Per Unit Output)</label>
                                                <input type="number" step="0.01" name="batch_size" id="batch_size" class="form-control-pro" value="1" placeholder="1.00">
                                                <small class="text-muted" style="font-size: 0.72rem;">Formula composition will be defined for 1 Unit / Batch</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing & Stock Alerts Card -->
                                <div class="section-card" id="pricing_card">
                                    <div class="card-header-pro">
                                        <h5 class="card-title-pro text-success">
                                            <i class="fa fa-tags"></i> 2. Pricing & Stock Alert Thresholds
                                        </h5>
                                    </div>
                                    <div class="card-body-pro">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label-pro">Sale Price Per Unit (PKR) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="sale_price_per_box" id="sale_price_per_box" class="form-control-pro font-weight-bold" required placeholder="0.00">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label-pro">Est. Production Cost (PKR)</label>
                                                <input type="number" step="0.01" name="purchase_price_per_piece" id="purchase_price_per_piece" class="form-control-pro" placeholder="0.00">
                                                <small class="text-muted" style="font-size: 0.72rem;">Auto-computed from raw materials or set manually</small>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label-pro">Wholesale Price (PKR)</label>
                                                <input type="number" step="0.01" name="wholesale_price" class="form-control-pro" placeholder="0.00">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label-pro">Min Stock Alert Level (Units)</label>
                                                <input type="number" name="alert_quantity" class="form-control-pro" value="10" placeholder="10">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label-pro">Reorder Carton / Bulk Threshold</label>
                                                <input type="number" name="alert_carton_quantity" class="form-control-pro" value="5" placeholder="5">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Image Uploader & Quick Specs -->
                            <div class="col-lg-4">
                                <div class="section-card">
                                    <div class="card-header-pro">
                                        <h5 class="card-title-pro text-dark">
                                            <i class="fa fa-image"></i> Product Image
                                        </h5>
                                    </div>
                                    <div class="card-body-pro text-center">
                                        <div class="img-uploader-box" onclick="document.getElementById('image').click();">
                                            <i class="fa fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                            <div class="font-weight-bold text-dark small mb-1">Click to Upload Image</div>
                                            <small class="text-muted d-block" style="font-size: 0.72rem;">PNG, JPG or WEBP up to 5MB</small>
                                            <img id="imagePreview" src="" style="display: none; max-width: 100%; max-height: 140px; border-radius: 8px; margin-top: 10px;">
                                        </div>
                                        <input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewProductImage(this);">
                                    </div>
                                </div>

                                <!-- Quick Summary Widget -->
                                <div class="p-3 bg-white rounded-3 border">
                                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.88rem;">
                                        <i class="fa fa-info-circle text-primary me-1"></i> Product Profile Setup
                                    </h6>
                                    <p class="text-muted small mb-0" style="line-height: 1.4;">
                                        After configuring product specifications in Step 1, click <strong>"Next: Composition & Raw Materials"</strong> to specify the exact raw materials required for 1 Unit / Batch output.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ============================================================== -->
                    <!-- STEP 2: COMPOSITION & RAW MATERIALS (BOM) -->
                    <!-- ============================================================== -->
                    <div class="wizard-step-panel" id="wizard_step_2">

                        <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 12px; background: #e0f2fe; color: #0369a1;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="font-size: 1.5rem;"><i class="fa fa-flask text-primary"></i></div>
                                <div>
                                    <strong class="font-weight-bold" style="font-size: 0.95rem;">Product Composition & Bill of Materials (BOM)</strong>
                                    <div class="small">Search raw materials or add new profiles on the fly. Pressing <kbd>Enter</kbd> or making a selection automatically opens the next row!</div>
                                </div>
                            </div>
                        </div>

                        <!-- 1. Raw Materials Composition Card -->
                        <div class="section-card">
                            <div class="card-header-pro py-2" style="background: #f0fdf4;">
                                <div class="card-title-pro text-success">
                                    <i class="fa fa-vials text-success"></i>
                                    <span>Raw Materials & Active Ingredients (Per 1 Unit / Batch)</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-success btn-sm font-weight-bold" data-bs-toggle="modal" data-bs-target="#quickCreateRmModal" data-toggle="modal" data-target="#quickCreateRmModal" style="border-radius: 8px; font-size: 0.8rem;">
                                        <i class="fa fa-plus-circle me-1"></i> + Create New Material
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm px-3 font-weight-bold" id="addRmBtn" style="border-radius: 8px; font-size: 0.8rem;">
                                        <i class="fa fa-plus me-1"></i> Add Row
                                    </button>
                                </div>
                            </div>

                            <div class="card-body-pro p-3">
                                <div id="raw_materials_container">
                                    <!-- Dynamic Raw Material Rows -->
                                </div>

                                <div id="no_rm_message" class="text-center text-muted py-4">
                                    <i class="fa fa-flask fa-2x mb-2 text-secondary"></i>
                                    <p class="mb-1 font-weight-bold text-dark">No raw materials added to formula yet.</p>
                                    <small class="text-muted">Click <strong>"+ Add Row"</strong> or <strong>"+ Create New Material"</strong> above to add ingredients.</small>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Packaging Materials Composition Card -->
                        <div class="section-card">
                            <div class="card-header-pro py-2" style="background: #fffbeb;">
                                <div class="card-title-pro text-warning">
                                    <i class="fa fa-box text-warning"></i>
                                    <span>Packaging Materials (Bottles, Caps, Cartons, Labels)</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold text-dark" data-bs-toggle="modal" data-bs-target="#quickCreatePmModal" data-toggle="modal" data-target="#quickCreatePmModal" style="border-radius: 8px; font-size: 0.8rem;">
                                        <i class="fa fa-plus-circle me-1"></i> + Create New Packaging
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm px-3 font-weight-bold text-dark" id="addPmBtn" style="border-radius: 8px; font-size: 0.8rem;">
                                        <i class="fa fa-plus me-1"></i> Add Row
                                    </button>
                                </div>
                            </div>

                            <div class="card-body-pro p-3">
                                <div id="packaging_materials_container">
                                    <!-- Dynamic Packaging Rows -->
                                </div>

                                <div id="no_pm_message" class="text-center text-muted py-4">
                                    <i class="fa fa-box-open fa-2x mb-2 text-secondary"></i>
                                    <p class="mb-1 font-weight-bold text-dark">No packaging items added yet.</p>
                                    <small class="text-muted">Click <strong>"+ Add Row"</strong> or <strong>"+ Create New Packaging"</strong> to include bottles or caps.</small>
                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- ============================================================== -->
                    <!-- STEP 3: REVIEW & FINAL SAVE -->
                    <!-- ============================================================== -->
                    <div class="wizard-step-panel" id="wizard_step_3">
                        <div class="section-card">
                            <div class="card-header-pro" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); color: #ffffff;">
                                <h5 class="card-title-pro text-white">
                                    <i class="fa fa-check-circle text-success"></i> Final Review & Verification Summary
                                </h5>
                                <span class="badge bg-success px-3 py-1 font-weight-bold">Ready to Save</span>
                            </div>

                            <div class="card-body-pro">
                                <div class="row g-4">
                                    <!-- Product Profile Summary -->
                                    <div class="col-md-5 border-end">
                                        <h6 class="font-weight-bold text-primary mb-3">
                                            <i class="fa fa-box me-1"></i> Product Profile Summary
                                        </h6>
                                        <div class="bg-light p-3 rounded-3 border">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted small">Product Name:</span>
                                                <span class="font-weight-bold text-dark" id="rev_product_name">-</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted small">Base Output Unit:</span>
                                                <span class="font-weight-bold text-primary" id="rev_unit">-</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted small">Category:</span>
                                                <span class="font-weight-bold text-dark" id="rev_category">-</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted small">Sale Price (Per Unit):</span>
                                                <span class="font-weight-bold text-success" id="rev_sale_price">Rs. 0.00</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted small">Min Stock Alert:</span>
                                                <span class="font-weight-bold text-dark" id="rev_alert_qty">10 Units</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Composition Recipe Summary -->
                                    <div class="col-md-7">
                                        <h6 class="font-weight-bold text-success mb-3">
                                            <i class="fa fa-flask me-1"></i> Composition & Raw Material Recipe Summary
                                        </h6>

                                        <div class="table-responsive border rounded-3">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="bg-light text-muted small">
                                                    <tr>
                                                        <th class="py-2 px-3">Type</th>
                                                        <th class="py-2 px-3">Material / Ingredient</th>
                                                        <th class="py-2 px-3 text-end">Quantity / 1 Unit</th>
                                                        <th class="py-2 px-3">Unit</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="rev_composition_body">
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-3">No composition items added</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- WIZARD NAVIGATION FOOTER ACTIONS -->
                    <div class="wizard-footer-actions mt-3">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 font-weight-bold" id="btn_prev_step" style="display: none; border-radius: 9px;" onclick="navigateStep(-1)">
                            <i class="fa fa-arrow-left me-1"></i> Previous Step
                        </button>

                        <div class="ms-auto d-flex gap-2">
                            <button type="button" class="btn btn-primary px-4 py-2 font-weight-bold" id="btn_next_step" style="border-radius: 9px;" onclick="navigateStep(1)">
                                Next: Composition Recipe <i class="fa fa-arrow-right ms-1"></i>
                            </button>

                            <button type="submit" class="btn btn-success px-5 py-2 font-weight-bold" id="btn_submit_final" style="display: none; border-radius: 9px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="fa fa-check-circle me-1"></i> Finalize & Save Product Profile
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- QUICK CREATE RAW MATERIAL MODAL (AJAX ZERO-REFRESH) -->
    <div class="modal fade" id="quickCreateRmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content style-modal" style="border-radius: 16px; border: 1.5px solid #e2e8f0; overflow: hidden;">
                <div class="modal-header bg-success text-white py-3 px-4">
                    <h5 class="modal-title font-weight-bold text-white mb-0">
                        <i class="fa fa-plus-circle me-2"></i> Add New Raw Material Profile
                    </h5>
                    <button type="button" class="close text-white border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" style="font-size: 1.5rem;">&times;</button>
                </div>
                <form id="quickRmForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label-pro">Raw Material Name <span class="text-danger">*</span></label>
                            <input type="text" id="q_rm_name" name="name" class="form-control-pro font-weight-bold" required placeholder="e.g. Paracetamol API, Liquid Base, Glucose">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-pro">Material Code <span class="text-danger">*</span></label>
                                <input type="text" id="q_rm_code" name="code" class="form-control-pro" required placeholder="RM-001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Material Type <span class="text-danger">*</span></label>
                                <select id="q_rm_type" name="type" class="form-select-pro" required>
                                    <option value="Ingredient">Ingredient</option>
                                    <option value="Chemical">Chemical</option>
                                    <option value="Powder">Powder</option>
                                    <option value="Liquid">Liquid</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-pro">Department <span class="text-danger">*</span></label>
                                <select id="q_rm_department" name="department_id" class="form-select-pro" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Unit <span class="text-danger">*</span></label>
                                <select id="q_rm_unit" name="unit_id" class="form-select-pro" required>
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label-pro">Price (Per Unit) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" id="q_rm_price" name="price" class="form-control-pro" value="0.00" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Min Stock Alert</label>
                                <input type="number" id="q_rm_min_stock" name="min_stock" class="form-control-pro" value="10">
                                <input type="hidden" name="status" value="1">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="button" id="saveQuickRmBtn" class="btn btn-success btn-sm px-4 font-weight-bold" style="border-radius: 8px;">
                            <i class="fa fa-check-circle me-1"></i> Save & Auto Select
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- QUICK CREATE PACKAGING MATERIAL MODAL (AJAX ZERO-REFRESH) -->
    <div class="modal fade" id="quickCreatePmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content style-modal" style="border-radius: 16px; border: 1.5px solid #e2e8f0; overflow: hidden;">
                <div class="modal-header bg-warning text-dark py-3 px-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fa fa-plus-circle me-2"></i> Add New Packaging Material Profile
                    </h5>
                    <button type="button" class="close text-dark border-0 bg-transparent" data-dismiss="modal" data-bs-dismiss="modal" style="font-size: 1.5rem;">&times;</button>
                </div>
                <form id="quickPmForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label-pro">Packaging Item Name <span class="text-danger">*</span></label>
                            <input type="text" id="q_pm_name" name="name" class="form-control-pro font-weight-bold" required placeholder="e.g. 120ml PET Bottle, Cap, Box Carton">
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-pro">Item Code <span class="text-danger">*</span></label>
                                <input type="text" id="q_pm_code" name="code" class="form-control-pro" required placeholder="PM-001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Packaging Type <span class="text-danger">*</span></label>
                                <select id="q_pm_type" name="packaging_type" class="form-select-pro" required>
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
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-pro">Department <span class="text-danger">*</span></label>
                                <select id="q_pm_department" name="department_id" class="form-select-pro" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-pro">Unit <span class="text-danger">*</span></label>
                                <select id="q_pm_unit" name="unit_id" class="form-select-pro" required>
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label-pro">Min Stock Alert</label>
                            <input type="number" id="q_pm_min_stock" name="min_stock" class="form-control-pro" value="50">
                            <input type="hidden" name="status" value="1">
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="button" id="saveQuickPmBtn" class="btn btn-warning btn-sm px-4 font-weight-bold text-dark" style="border-radius: 8px;">
                            <i class="fa fa-check-circle me-1"></i> Save & Auto Select
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/vendors/select2/js/select2.min.js') }}"></script>

    <script>
        // Available Raw Materials Data
        let rawMaterialsData = [
            @foreach($rawMaterials as $rm)
                { id: {{ $rm->id }}, name: "{{ addslashes($rm->name) }}", code: "{{ $rm->code }}", unit: "{{ $rm->unit->name ?? 'Unit' }}", unit_id: {{ $rm->unit_id ?? 'null' }} },
            @endforeach
        ];

        // Available Packaging Materials Data
        let packagingMaterialsData = [
            @foreach($packagingMaterials as $pm)
                { id: {{ $pm->id }}, name: "{{ addslashes($pm->name) }}", code: "{{ $pm->code }}", unit: "{{ $pm->unit->name ?? 'Pcs' }}" },
            @endforeach
        ];

        let currentWizardStep = 1;
        let rmCounter = 0;
        let pmCounter = 0;

        function showAlert(title, message, icon = 'warning') {
            if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
                Swal.fire(title, message, icon);
            } else {
                alert(title + ': ' + message);
            }
        }

        function toggleProductType() {
            let type = $('#product_type').val();
            if(type === 'Finished Good') {
                $('#pricing_card').show();
                $('#sale_price_per_box').attr('required', 'required');
                
                // Hide steps in wizard
                $('#step_tab_2, #step_line_2, #step_tab_3, #step_line_3').hide();
                
                // If we are past step 1, go back to step 1
                if(currentWizardStep > 1) {
                    switchStep(1);
                } else {
                    $('#btn_next_step').hide();
                    $('#btn_submit_final').show();
                }
            } else {
                $('#pricing_card').hide();
                $('#sale_price_per_box').removeAttr('required');
                
                // Show steps in wizard
                $('#step_tab_2, #step_line_2, #step_tab_3, #step_line_3').show();
                
                if(currentWizardStep === 1) {
                    $('#btn_next_step').show().html('Next: Composition Recipe <i class="fa fa-arrow-right ms-1"></i>');
                    $('#btn_submit_final').hide();
                }
            }
        }

        function switchStep(step) {
            if (step > currentWizardStep) {
                // Validate current step before proceeding forward
                if (currentWizardStep === 1) {
                    const name = $('#product_name').val() ? $('#product_name').val().trim() : '';
                    const unit = $('#product_unit').val();
                    const category = $('#category_id').val();
                    const price = $('#sale_price_per_box').val();

                    if (!name) {
                        showAlert('Required Field', 'Please enter the Product Name before proceeding.', 'warning');
                        $('#product_name').focus();
                        return;
                    }
                    if (!unit) {
                        showAlert('Required Field', 'Please select a Base Unit (UOM).', 'warning');
                        $('#product_unit').focus();
                        return;
                    }
                    if (!category) {
                        showAlert('Required Field', 'Please select a Product Category.', 'warning');
                        $('#category_id').focus();
                        return;
                    }
                    let type = $('#product_type').val();
                    if (type === 'Finished Good' && (price === '' || price === null || price === undefined)) {
                        showAlert('Required Field', 'Please enter Sale Price per unit.', 'warning');
                        $('#sale_price_per_box').focus();
                        return;
                    }
                }
            }

            currentWizardStep = step;

            // Toggle step panels
            $('.wizard-step-panel').removeClass('active');
            $('#wizard_step_' + step).addClass('active');

            // Update Header Indicators
            $('.wizard-step-item').removeClass('active completed');
            for (let i = 1; i <= 3; i++) {
                if (i < step) {
                    $('#step_tab_' + i).addClass('completed');
                    $('#step_line_' + i).addClass('active');
                } else if (i === step) {
                    $('#step_tab_' + i).addClass('active');
                } else {
                    $('#step_line_' + i).removeClass('active');
                }
            }

            // Update Navigation Footer Buttons
            if (step === 1) {
                $('#btn_prev_step').hide();
                if ($('#product_type').val() === 'Finished Good') {
                    $('#btn_next_step').hide();
                    $('#btn_submit_final').show();
                } else {
                    $('#btn_next_step').show().html('Next: Composition Recipe <i class="fa fa-arrow-right ms-1"></i>');
                    $('#btn_submit_final').hide();
                }
            } else if (step === 2) {
                $('#btn_prev_step').show();
                $('#btn_next_step').show().html('Next: Review Profile <i class="fa fa-arrow-right ms-1"></i>');
                $('#btn_submit_final').hide();
                $('#summary_product_title').text($('#product_name').val() || 'This Product');
            } else if (step === 3) {
                $('#btn_prev_step').show();
                $('#btn_next_step').hide();
                $('#btn_submit_final').show();
                buildReviewSummary();
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function navigateStep(direction) {
            switchStep(currentWizardStep + direction);
        }

        // Generate Options HTML for Raw Material Selects
        function getRmOptionsHtml() {
            let optionsHtml = '<option value="">-- Search & Choose Raw Material --</option>';
            rawMaterialsData.forEach(rm => {
                optionsHtml += `<option value="${rm.id}" data-unit="${rm.unit}" data-unit-id="${rm.unit_id || ''}">${rm.name} (${rm.code})</option>`;
            });
            return optionsHtml;
        }

        // Add Raw Material Row with Searchable Select2 & Auto-Next Row
        function addRawMaterialRow(autoSelectId = null) {
            rmCounter++;
            const optionsHtml = getRmOptionsHtml();

            const html = `
                <div class="comp-item-row rm-row" id="rm_row_${rmCounter}" data-counter="${rmCounter}">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label-pro" style="font-size: 0.68rem;">Raw Material / Ingredient</label>
                            <select name="raw_material_id[]" class="form-select-pro rm-select select2-rm">
                                ${optionsHtml}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-pro" style="font-size: 0.68rem;">Qty / 1 Unit</label>
                            <input type="number" step="0.0001" name="rm_quantity[]" class="form-control-pro font-weight-bold rm-qty" placeholder="0.00">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label-pro" style="font-size: 0.68rem;">Unit</label>
                            <input type="hidden" name="rm_unit_id[]" class="rm-unit-id-val">
                            <input type="text" class="form-control-pro rm-unit-label" placeholder="mL / mg / Ltr" readonly style="background: #eef2ff; font-weight: 600;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-pro" style="font-size: 0.68rem;">Mixing Notes / Stage</label>
                            <input type="text" name="rm_notes[]" class="form-control-pro rm-notes" placeholder="Press Enter for next row...">
                        </div>
                        <div class="col-md-1 text-end pt-3">
                            <button type="button" class="btn btn-outline-danger btn-sm p-1" style="border-radius: 6px;" onclick="removeRmRow(${rmCounter})">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('#raw_materials_container').append(html);
            $('#no_rm_message').hide();

            const $row = $('#rm_row_' + rmCounter);
            const $select = $row.find('.rm-select');

            // Initialize Searchable Select2
            $select.select2({
                placeholder: '-- Search Raw Material --',
                width: '100%'
            });

            if (autoSelectId) {
                $select.val(autoSelectId).trigger('change');
            }

            // On Change: Update Unit & Auto-Append next row if last row
            $select.on('change', function() {
                updateRmUnit(this);
                checkAutoAppendRm($row);
            });

            // On Enter Key in Inputs: Auto-append next row
            $row.find('.rm-qty, .rm-notes').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    checkAutoAppendRm($row, true);
                }
            });
        }

        function checkAutoAppendRm($row, forceNext = false) {
            const isLast = $row.is(':last-child');
            if (isLast) {
                addRawMaterialRow();
            }
            if (forceNext) {
                const $nextRow = $row.next('.rm-row');
                if ($nextRow.length) {
                    $nextRow.find('.rm-select').select2('open');
                }
            }
        }

        function removeRmRow(id) {
            $('#rm_row_' + id).remove();
            if ($('.rm-row').length === 0) {
                $('#no_rm_message').show();
            }
        }

        function updateRmUnit(selectEl) {
            const selectedOpt = $(selectEl).find('option:selected');
            const unitName = selectedOpt.data('unit') || 'Unit';
            const unitId = selectedOpt.data('unit-id') || '';
            const $row = $(selectEl).closest('.comp-item-row');
            $row.find('.rm-unit-label').val(unitName);
            $row.find('.rm-unit-id-val').val(unitId);
        }

        // Generate Options HTML for Packaging Material Selects
        function getPmOptionsHtml() {
            let optionsHtml = '<option value="">-- Search & Choose Packaging Item --</option>';
            packagingMaterialsData.forEach(pm => {
                optionsHtml += `<option value="${pm.id}" data-unit="${pm.unit}">${pm.name} (${pm.code})</option>`;
            });
            return optionsHtml;
        }

        // Add Packaging Material Row with Searchable Select2 & Auto-Next Row
        function addPackagingRow(autoSelectId = null) {
            pmCounter++;
            const optionsHtml = getPmOptionsHtml();

            const html = `
                <div class="comp-item-row pm-row" id="pm_row_${pmCounter}" data-counter="${pmCounter}">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <label class="form-label-pro" style="font-size: 0.68rem;">Packaging Material Item</label>
                            <select name="packaging_material_id[]" class="form-select-pro pm-select select2-pm">
                                ${optionsHtml}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-pro" style="font-size: 0.68rem;">Qty Needed Per Unit</label>
                            <input type="number" step="0.01" name="pm_quantity[]" class="form-control-pro font-weight-bold pm-qty" placeholder="1.00" value="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-pro" style="font-size: 0.68rem;">Notes</label>
                            <input type="text" name="pm_notes[]" class="form-control-pro pm-notes" placeholder="Press Enter for next row...">
                        </div>
                        <div class="col-md-1 text-end pt-3">
                            <button type="button" class="btn btn-outline-danger btn-sm p-1" style="border-radius: 6px;" onclick="removePmRow(${pmCounter})">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('#packaging_materials_container').append(html);
            $('#no_pm_message').hide();

            const $row = $('#pm_row_' + pmCounter);
            const $select = $row.find('.pm-select');

            // Initialize Searchable Select2
            $select.select2({
                placeholder: '-- Search Packaging Item --',
                width: '100%'
            });

            if (autoSelectId) {
                $select.val(autoSelectId).trigger('change');
            }

            // On Change: Auto-Append next row if last
            $select.on('change', function() {
                checkAutoAppendPm($row);
            });

            // On Enter Key: Auto-append next row
            $row.find('.pm-qty, .pm-notes').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    checkAutoAppendPm($row, true);
                }
            });
        }

        function checkAutoAppendPm($row, forceNext = false) {
            const isLast = $row.is(':last-child');
            if (isLast) {
                addPackagingRow();
            }
            if (forceNext) {
                const $nextRow = $row.next('.pm-row');
                if ($nextRow.length) {
                    $nextRow.find('.pm-select').select2('open');
                }
            }
        }

        function removePmRow(id) {
            $('#pm_row_' + id).remove();
            if ($('.pm-row').length === 0) {
                $('#no_pm_message').show();
            }
        }

        // --- QUICK CREATE RAW MATERIAL (AJAX ZERO-REFRESH) ---
        $('#saveQuickRmBtn').on('click', function() {
            const name = $('#q_rm_name').val().trim();
            const code = $('#q_rm_code').val().trim();
            if (!name || !code) {
                Swal.fire('Required Fields', 'Please fill Raw Material Name and Code.', 'warning');
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

            $.ajax({
                url: "{{ route('raw_materials.store') }}",
                type: 'POST',
                data: $('#quickRmForm').serialize(),
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Save & Auto Select');
                    if (res.errors) {
                        let errMsg = Object.values(res.errors).flat().join('<br>');
                        Swal.fire('Error', errMsg, 'error');
                        return;
                    }

                    if (res.data) {
                        const newItem = {
                            id: res.data.id,
                            name: res.data.name,
                            code: res.data.code,
                            unit: res.data.unit ? res.data.unit.name : 'Unit'
                        };
                        rawMaterialsData.push(newItem);

                        // Update options across all existing raw material selects
                        const newOption = new Option(`${newItem.name} (${newItem.code})`, newItem.id, false, false);
                        $(newOption).attr('data-unit', newItem.unit);

                        $('.rm-select').each(function() {
                            const val = $(this).val();
                            $(this).append($(newOption).clone()).val(val).trigger('change.select2');
                        });

                        // Close Modal
                        $('#quickCreateRmModal').modal('hide');
                        $('#quickRmForm')[0].reset();
                        $('#q_rm_code').val('RM-' + Math.floor(1000 + Math.random() * 9000));

                        // Select in current empty row or add new row
                        let $targetRow = $('.rm-row:last');
                        if (!$targetRow.length || $targetRow.find('.rm-select').val()) {
                            addRawMaterialRow(newItem.id);
                        } else {
                            $targetRow.find('.rm-select').val(newItem.id).trigger('change');
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `Raw Material "${newItem.name}" Created & Selected!`,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Save & Auto Select');
                    Swal.fire('Error', 'Failed to create Raw Material.', 'error');
                }
            });
        });

        // --- QUICK CREATE PACKAGING MATERIAL (AJAX ZERO-REFRESH) ---
        $('#saveQuickPmBtn').on('click', function() {
            const name = $('#q_pm_name').val().trim();
            const code = $('#q_pm_code').val().trim();
            if (!name || !code) {
                Swal.fire('Required Fields', 'Please fill Packaging Item Name and Code.', 'warning');
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

            $.ajax({
                url: "{{ route('packaging_materials.store') }}",
                type: 'POST',
                data: $('#quickPmForm').serialize(),
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Save & Auto Select');
                    if (res.errors) {
                        let errMsg = Object.values(res.errors).flat().join('<br>');
                        Swal.fire('Error', errMsg, 'error');
                        return;
                    }

                    if (res.data) {
                        const newItem = {
                            id: res.data.id,
                            name: res.data.name,
                            code: res.data.code,
                            unit: res.data.unit ? res.data.unit.name : 'Pcs'
                        };
                        packagingMaterialsData.push(newItem);

                        // Update options across all existing packaging selects
                        const newOption = new Option(`${newItem.name} (${newItem.code})`, newItem.id, false, false);
                        $(newOption).attr('data-unit', newItem.unit);

                        $('.pm-select').each(function() {
                            const val = $(this).val();
                            $(this).append($(newOption).clone()).val(val).trigger('change.select2');
                        });

                        // Close Modal
                        $('#quickCreatePmModal').modal('hide');
                        $('#quickPmForm')[0].reset();
                        $('#q_pm_code').val('PM-' + Math.floor(1000 + Math.random() * 9000));

                        // Select in current empty row or add new row
                        let $targetRow = $('.pm-row:last');
                        if (!$targetRow.length || $targetRow.find('.pm-select').val()) {
                            addPackagingRow(newItem.id);
                        } else {
                            $targetRow.find('.pm-select').val(newItem.id).trigger('change');
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `Packaging Item "${newItem.name}" Created & Selected!`,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Save & Auto Select');
                    Swal.fire('Error', 'Failed to create Packaging Material.', 'error');
                }
            });
        });

        // Build Review Summary in Step 3
        function buildReviewSummary() {
            $('#rev_product_name').text($('#product_name').val() || '-');
            $('#rev_unit').text($('#product_unit option:selected').text() || $('#product_unit').val() || '-');
            $('#rev_category').text($('#category_id option:selected').text() || '-');
            $('#rev_sale_price').text('Rs. ' + (parseFloat($('#sale_price_per_box').val()) || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }));
            $('#rev_alert_qty').text(($('input[name="alert_quantity"]').val() || '10') + ' Units');

            let tbodyHtml = '';

            // Raw Materials
            $('.rm-row').each(function() {
                const name = $(this).find('.rm-select option:selected').text();
                const qty = $(this).find('.rm-qty').val();
                const unit = $(this).find('.rm-unit-label').val();
                if (name && qty && $(this).find('.rm-select').val()) {
                    tbodyHtml += `
                        <tr>
                            <td class="py-2 px-3"><span class="badge bg-success-subtle text-success border border-success">Raw Material</span></td>
                            <td class="py-2 px-3 font-weight-bold text-dark">${name}</td>
                            <td class="py-2 px-3 text-end font-weight-bold text-primary">${qty}</td>
                            <td class="py-2 px-3">${unit}</td>
                        </tr>
                    `;
                }
            });

            // Packaging Materials
            $('.pm-row').each(function() {
                const name = $(this).find('.pm-select option:selected').text();
                const qty = $(this).find('.pm-qty').val();
                if (name && qty && $(this).find('.pm-select').val()) {
                    tbodyHtml += `
                        <tr>
                            <td class="py-2 px-3"><span class="badge bg-warning-subtle text-warning-emphasis border border-warning">Packaging</span></td>
                            <td class="py-2 px-3 font-weight-bold text-dark">${name}</td>
                            <td class="py-2 px-3 text-end font-weight-bold text-primary">${qty}</td>
                            <td class="py-2 px-3">Pcs</td>
                        </tr>
                    `;
                }
            });

            if (!tbodyHtml) {
                tbodyHtml = '<tr><td colspan="4" class="text-center text-muted py-3">No composition items added to this product.</td></tr>';
            }

            $('#rev_composition_body').html(tbodyHtml);
        }

        // Image Preview
        function previewProductImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Subcategory Dependent Dropdown
        function fetchSubcategories(categoryId) {
            const subCategorySelect = $('#sub_category_id');
            subCategorySelect.html('<option value="">-- Select Subcategory --</option>');

            if (categoryId) {
                $.ajax({
                    url: "{{ url('/get-subcategories') }}/" + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        subCategorySelect.empty();
                        subCategorySelect.append('<option value="">-- Select Subcategory --</option>');
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(sub => {
                                subCategorySelect.append(`<option value="${sub.id}">${sub.name}</option>`);
                            });
                        } else {
                            subCategorySelect.html('<option value="">-- No Subcategories Available --</option>');
                        }
                        if ($.fn.select2 && subCategorySelect.hasClass('select2-hidden-accessible')) {
                            subCategorySelect.trigger('change.select2');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch subcategories:', error);
                    }
                });
            } else {
                if ($.fn.select2 && subCategorySelect.hasClass('select2-hidden-accessible')) {
                    subCategorySelect.trigger('change.select2');
                }
            }
        }

        // Global Event Delegation for Category Change
        $(document).on('change select2:select', '#category_id', function() {
            const catId = $(this).val();
            fetchSubcategories(catId);
        });

        $(document).ready(function() {
            $('.form-select-pro').select2({
                width: '100%',
                placeholder: "-- Select --",
                allowClear: false
            });

            toggleProductType();

            $('#addRmBtn').on('click', () => addRawMaterialRow());
            $('#addPmBtn').on('click', () => addPackagingRow());

            // Set initial auto-generated codes for quick modals
            $('#q_rm_code').val('RM-' + Math.floor(1000 + Math.random() * 9000));
            $('#q_pm_code').val('PM-' + Math.floor(1000 + Math.random() * 9000));

            // Add initial rows
            addRawMaterialRow();
            addPackagingRow();

            // Auto-fetch subcategories if category is pre-selected
            if ($('#category_id').val()) {
                fetchSubcategories($('#category_id').val());
            }

            // Fast AJAX Zero-Refresh Form Submission with Instant Visual Feedback
            $('#productWizardForm').on('submit', function(e) {
                e.preventDefault();

                // 1. Remove blank rows
                $('.rm-row').each(function() {
                    if (!$(this).find('.rm-select').val()) $(this).remove();
                });
                $('.pm-row').each(function() {
                    if (!$(this).find('.pm-select').val()) $(this).remove();
                });

                // 2. Instant visual UI feedback
                const submitBtn = $('#btn_submit_final');
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Saving Product Profile...');

                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(res) {
                        if (res.status === 'error') {
                            submitBtn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Finalize & Save Product Profile');
                            let errMsg = res.errors ? Object.values(res.errors).flat().join('<br>') : (res.message || 'Validation failed');
                            showAlert('Validation Error', errMsg, 'error');
                            return;
                        }

                        if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Product Saved Successfully!',
                                text: 'Product profile and composition recipe created in database.',
                                showCancelButton: true,
                                confirmButtonText: 'Go to Products List',
                                cancelButtonText: '+ Create Another',
                                confirmButtonColor: '#10b981',
                                cancelButtonColor: '#3b82f6'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('product') }}";
                                } else {
                                    window.location.reload();
                                }
                            });
                        } else {
                            alert('Product Saved Successfully!');
                            window.location.href = "{{ route('product') }}";
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="fa fa-check-circle me-1"></i> Finalize & Save Product Profile');
                        let errMsg = 'Failed to save product profile. Please check inputs.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        showAlert('Save Error', errMsg, 'error');
                    }
                });
            });
        });
    </script>
@endsection
