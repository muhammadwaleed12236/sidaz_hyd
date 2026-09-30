@extends('admin_panel.layout.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .mpur-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 50px;
    }

    .mpur-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 28px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .mpur-title {
        font-weight: 800;
        font-size: 1.4rem;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .mpur-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .card-section-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
        padding-bottom: 10px;
        border-bottom: 2px dashed #e2e8f0;
        margin-bottom: 20px;
    }

    /* EXCEL SPREADSHEET TABLE GRID STYLING */
    .table-excel-container {
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        background-color: #ffffff;
    }

    .table-excel {
        border-collapse: collapse !important;
        width: 100%;
        margin-bottom: 0 !important;
        background-color: #ffffff;
    }

    .table-excel th {
        background: #f1f5f9 !important;
        color: #334155 !important;
        font-size: 0.72rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 8px 10px !important;
        border: 1px solid #cbd5e1 !important;
        vertical-align: middle;
        text-align: center;
        user-select: none;
    }

    .table-excel td {
        padding: 0 !important;
        margin: 0 !important;
        border: 1px solid #cbd5e1 !important;
        vertical-align: middle;
        background: #ffffff;
        position: relative;
    }

    .table-excel td.excel-row-num {
        background-color: #f8fafc !important;
        color: #64748b !important;
        font-weight: 700;
        font-size: 0.8rem;
        text-align: center;
        width: 36px;
        padding: 8px 4px !important;
        user-select: none;
        border-right: 2px solid #cbd5e1 !important;
    }

    /* Excel Cell Inputs - Seamless & Edge to Edge */
    .excel-input {
        width: 100% !important;
        height: 38px !important;
        border: none !important;
        border-radius: 0 !important;
        padding: 6px 10px !important;
        font-size: 0.85rem !important;
        background: transparent !important;
        outline: none !important;
        box-shadow: none !important;
        color: #1e293b;
        margin: 0 !important;
    }

    .excel-input:focus {
        background-color: #f0fdf4 !important; /* MS Excel soft green active cell tint */
        outline: 2px solid #16a34a !important; /* Excel green outline */
        outline-offset: -2px;
        z-index: 10;
        position: relative;
    }

    .excel-input-readonly {
        background-color: #f8fafc !important;
        color: #2563eb !important;
        font-weight: 700 !important;
        cursor: default;
    }

    /* Select2 integration into Excel cells */
    .table-excel .select2-container--default .select2-selection--single {
        border: none !important;
        border-radius: 0 !important;
        height: 38px !important;
        padding: 4px 6px !important;
        background: transparent !important;
        display: flex !important;
        align-items: center !important;
    }

    .table-excel .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        font-size: 0.85rem !important;
        color: #1e293b !important;
        padding-left: 4px !important;
        width: 100%;
    }

    .table-excel .select2-container--default.select2-container--focus .select2-selection--single,
    .table-excel .select2-container--default.select2-container--open .select2-selection--single {
        outline: 2px solid #16a34a !important;
        outline-offset: -2px;
        background-color: #f0fdf4 !important;
        z-index: 10;
    }

    .table-excel .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 4px !important;
    }

    /* Excel Date Cells */
    .excel-date-box {
        display: flex;
        flex-direction: column;
        gap: 2px;
        padding: 3px 4px;
    }

    .excel-date-input {
        height: 22px !important;
        font-size: 0.73rem !important;
        padding: 1px 4px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 2px !important;
        background: #ffffff;
    }

    .excel-date-input:focus {
        border-color: #16a34a !important;
        outline: 1px solid #16a34a !important;
        background-color: #f0fdf4 !important;
    }

    /* Unit Column Badge */
    .excel-unit-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        background-color: #fafafa;
        color: #475569;
        font-weight: 600;
        font-size: 0.78rem;
    }

    /* Action Delete Cell */
    .btn-excel-delete {
        background: transparent;
        border: none;
        color: #94a3b8;
        width: 100%;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-excel-delete:hover {
        background-color: #fef2f2;
        color: #ef4444;
    }

    /* Excel validation highlight */
    .table-excel .is-invalid-custom,
    .table-excel .select2-container--default .select2-selection--single.is-invalid-custom {
        outline: 2px solid #ef4444 !important;
        outline-offset: -2px;
        background-color: #fef2f2 !important;
    }

    .btn-add-row {
        background: #f0fdf4;
        color: #166534;
        border: 1.5px dashed #86efac;
        padding: 10px 20px;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 10px;
        transition: all 0.2s ease;
        width: 100%;
    }

    .btn-add-row:hover {
        background: #dcfce7;
        border-color: #4ade80;
        color: #14532d;
    }

    .summary-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 0.85rem;
    }

    .summary-row.total-row {
        border-top: 2px solid #cbd5e1;
        padding-top: 12px;
        margin-top: 8px;
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
    }

    /* Custom Field Validation Highlight */
    .is-invalid-custom {
        border: 2px solid #ef4444 !important;
        background-color: #fef2f2 !important;
    }

    .select2-container--default .select2-selection--single.is-invalid-custom {
        border: 2px solid #ef4444 !important;
        background-color: #fef2f2 !important;
    }

    /* Side-by-Side Vendor Input Group */
    .vendor-input-group {
        display: flex;
        align-items: stretch;
        width: 100%;
    }
    .vendor-input-group .select2-container {
        flex: 1 1 auto !important;
        width: 1% !important;
    }
    .vendor-input-group .select2-selection {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        height: 38px !important;
        display: flex !important;
        align-items: center !important;
    }
    .vendor-input-group .btn-add-vendor {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0 14px;
        white-space: nowrap;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .vendor-input-group .btn-add-vendor:hover {
        background: #1d4ed8;
        color: #ffffff !important;
    }

    .error-banner {
        display: none;
        background: #fef2f2;
        border: 1.5px solid #fca5a5;
        color: #991b1b;
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
</style>

<div class="mpur-page container-fluid px-3 px-md-4 pt-3">
    <!-- HEADER BARS -->
    <div class="mpur-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                <i class="fas fa-shopping-cart fa-lg"></i>
            </div>
            <div>
                <h3 class="mpur-title mb-0">New Material Purchase</h3>
                <span class="text-muted small">Record purchase invoices for Raw Materials and Packaging Materials</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border px-3 py-2 fw-bold" style="font-size: 0.85rem;">
                <i class="fas fa-barcode text-primary me-1"></i> Invoice: {{ $nextInvoice }}
            </span>
            <a href="{{ route('material-purchases.index') }}" class="btn btn-outline-secondary px-4 fw-semibold" style="border-radius: 10px;">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- CLIENT & SERVER VALIDATION ERROR CONTAINER (INLINE NO-REFRESH) -->
    <div class="error-banner shadow-sm" id="ajaxErrorContainer">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                <span id="ajaxErrorMessage">Please fill out all required fields marked in red.</span>
            </div>
            <button type="button" class="btn-close" onclick="$('#ajaxErrorContainer').slideUp()"></button>
        </div>
        <ul id="ajaxErrorList" class="mb-0 mt-2 small text-danger ps-3" style="display:none;"></ul>
    </div>

    <!-- MAIN FORM CONTAINER -->
    <div class="mpur-card">
        <form action="{{ route('material-purchases.store') }}" method="POST" id="purchaseForm" novalidate>
            @csrf
            <div class="p-4">
                
                <!-- HEADER DETAILS -->
                <div class="card-section-title d-flex align-items-center gap-2">
                    <i class="fas fa-file-invoice text-primary"></i> 1. Invoice & Vendor Details
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Invoice Number</label>
                        <input type="text" name="invoice_no" class="form-control fw-bold bg-light" value="{{ $nextInvoice }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Purchase Date <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" id="purchase_date" class="form-control fw-semibold" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Vendor / Supplier <span class="text-danger">*</span></label>
                        <div class="vendor-input-group">
                            <select name="vendor_id" id="vendor_id" class="form-control select2" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn-add-vendor" id="btnOpenVendorModal" data-toggle="modal" data-target="#quickAddVendorModal" data-bs-toggle="modal" data-bs-target="#quickAddVendorModal" title="Add New Vendor">
                                <i class="fas fa-plus me-1"></i> New
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Purchase Type <span class="text-danger">*</span></label>
                        <select name="purchase_type" id="purchase_type" class="form-control select2" required onchange="filterItems()">
                            <option value="Raw Material">Raw Material Only</option>
                            <option value="Packaging">Packaging Material Only</option>
                            <option value="Mixed">Mixed (Raw & Packaging)</option>
                        </select>
                    </div>
                </div>

                <!-- ITEMS TABLE -->
                <div class="card-section-title d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-boxes text-primary"></i> 2. Purchase Items List
                        <small class="text-muted font-weight-normal ms-2">(Press <kbd class="bg-light text-dark border">Enter</kbd> in inputs to add new row)</small>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-1" style="font-size: 0.75rem;" id="itemsCountBadge">1 Item Row</span>
                </div>

                <div class="table-excel-container mb-3">
                    <table class="table-excel" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width: 36px;">#</th>
                                <th style="min-width: 220px;">Material Item <span class="text-danger">*</span></th>
                                <th style="width: 95px;">Qty <span class="text-danger">*</span></th>
                                <th style="width: 70px;">Unit</th>
                                <th style="width: 110px;">Unit Price (Rs) <span class="text-danger">*</span></th>
                                <th style="width: 90px;">Disc (Rs)</th>
                                <th style="width: 90px;">Tax (Rs)</th>
                                <th style="width: 100px;">Batch No</th>
                                <th style="width: 135px;">Mfg / Exp Dates</th>
                                <th style="width: 115px;" class="text-end pe-2">Subtotal</th>
                                <th style="width: 40px;">×</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <tr class="item-row">
                                <td class="excel-row-num row-number">1</td>
                                <td>
                                    <select name="item_type[]" class="d-none row-item-type"><option value="RawMaterial"></option></select>
                                    <select name="item_id[]" class="form-control select2 item-select" required onchange="updateRowItemType(this)">
                                        <option value="">Select Item</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.0001" name="qty[]" class="excel-input item-qty fw-semibold text-end" placeholder="0.00" required oninput="calcSubtotal(this)">
                                </td>
                                <td>
                                    <div class="excel-unit-cell">
                                        <span class="item-unit">-</span>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="unit_price[]" class="excel-input item-price fw-semibold text-end" placeholder="0.00" required oninput="calcSubtotal(this)">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="discount[]" class="excel-input item-disc text-danger text-end" value="0" oninput="calcSubtotal(this)">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="tax[]" class="excel-input item-tax text-info text-end" value="0" oninput="calcSubtotal(this)">
                                </td>
                                <td>
                                    <input type="text" name="batch_no[]" class="excel-input" placeholder="Batch #">
                                </td>
                                <td>
                                    <div class="excel-date-box">
                                        <input type="date" name="mfg_date[]" class="excel-date-input" title="Manufacturing Date">
                                        <input type="date" name="exp_date[]" class="excel-date-input" title="Expiry Date">
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="excel-input item-subtotal excel-input-readonly text-end pe-2" readonly value="0.00">
                                </td>
                                <td>
                                    <button type="button" class="btn-excel-delete remove-row" title="Delete Row">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mb-4">
                    <button type="button" class="btn-add-row" onclick="addRow()">
                        <i class="fas fa-plus me-1"></i> Add Another Material Item Row
                    </button>
                </div>

                <!-- TRANSPORT & SUMMARY SECTION -->
                <div class="row g-4 mb-3">
                    <div class="col-md-7">
                        <div class="card-section-title d-flex align-items-center gap-2">
                            <i class="fas fa-truck text-primary"></i> 3. Transport Details & Remarks
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Transport Name</label>
                                <input type="text" name="transport_name" class="form-control" placeholder="e.g. Faisal Movers / Cargo">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Driver Name</label>
                                <input type="text" name="driver_name" class="form-control" placeholder="Driver Full Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Vehicle / Truck No.</label>
                                <input type="text" name="vehicle_no" class="form-control" placeholder="e.g. LES-1234">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Transport Freight Charges (Rs)</label>
                                <input type="number" step="0.01" name="transport_charges" id="transport_charges" class="form-control fw-semibold" placeholder="0.00" oninput="calcTotal()">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Purchase Remarks / Notes</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Enter any extra notes or instructions for this purchase..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card-section-title d-flex align-items-center gap-2">
                            <i class="fas fa-calculator text-primary"></i> 4. Payment & Grand Totals
                        </div>
                        <div class="summary-card shadow-sm">
                            <div class="summary-row">
                                <span class="text-secondary fw-semibold">Items Subtotal:</span>
                                <span class="fw-bold text-dark" id="lblSubtotal">Rs. 0.00</span>
                            </div>
                            <div class="summary-row">
                                <span class="text-secondary fw-semibold">Total Line Discount:</span>
                                <span class="fw-bold text-danger" id="lblDiscount">- Rs. 0.00</span>
                            </div>
                            <div class="summary-row">
                                <span class="text-secondary fw-semibold">Total Tax:</span>
                                <span class="fw-bold text-info" id="lblTax">+ Rs. 0.00</span>
                            </div>
                            <div class="summary-row">
                                <span class="text-secondary fw-semibold">Freight / Transport:</span>
                                <span class="fw-bold text-secondary" id="lblTransport">+ Rs. 0.00</span>
                            </div>
                            
                            <div class="summary-row total-row">
                                <span>Grand Total Amount:</span>
                                <span class="text-primary" id="lblTotal">Rs. 0.00</span>
                            </div>

                            <div class="mt-3 pt-3 border-top">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Payment Terms / Method</label>
                                    <select name="payment_method" id="payment_method" class="form-control select2 fw-semibold">
                                        <option value="Credit">Credit (Unpaid / Payable)</option>
                                        <option value="Cash">Cash Payment</option>
                                        <option value="Bank">Bank Transfer</option>
                                        <option value="Cheque">Cheque Payment</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3 d-none" id="paymentAccountDiv">
                                    <label class="form-label small fw-bold">Paying From Account <span class="text-danger">*</span></label>
                                    <select name="payment_account_id" id="payment_account_id" class="form-control select2">
                                        <option value="">Select Cash/Bank Account</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Amount Paid Now (Rs)</label>
                                    <input type="number" step="0.01" name="paid_amount" id="paidAmount" class="form-control fw-bold text-success" value="0" readonly oninput="calcTotal()">
                                </div>
                                
                                <div class="summary-row pt-2 border-top">
                                    <span class="fw-bold text-dark">Remaining Balance Due:</span>
                                    <span class="fw-bold text-danger fs-6" id="lblBalance">Rs. 0.00</span>
                                </div>
                                <input type="hidden" name="payment_status" id="payment_status" value="Pending">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- STICKY BOTTOM ACTION FOOTER -->
            <div class="bg-light px-4 py-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="fas fa-shield-alt text-success me-1"></i> Data validation active. Unsaved fields will not be cleared on error.
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="hidden" name="invoice_status" id="invoice_status" value="completed">
                    
                    <button type="button" class="btn btn-outline-secondary px-4 fw-bold py-2" id="btnSaveDraft" onclick="submitFormInteractive('draft')" style="border-radius: 10px;">
                        <i class="fas fa-bookmark me-1"></i> Save as Draft
                    </button>
                    
                    <button type="button" class="btn btn-primary px-5 fw-bold py-2 shadow-sm" id="btnSubmitComplete" onclick="submitFormInteractive('completed')" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none; border-radius: 10px;">
                        <i class="fas fa-check-circle me-1"></i> Complete Purchase Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: QUICK ADD NEW VENDOR -->
<div class="modal fade" id="quickAddVendorModal" tabindex="-1" aria-labelledby="quickAddVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-bottom py-3">
                <h6 class="modal-title fw-bold text-dark mb-0" id="quickAddVendorModalLabel">
                    <i class="fas fa-user-plus text-primary me-2"></i>Quick Add New Vendor / Supplier
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formQuickAddVendor">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Vendor Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="vendorNameInput" class="form-control" placeholder="Enter vendor name..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Company / Business Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g. ABC Pharma Suppliers">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mobile / Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="0300-1234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Opening Balance (Rs)</label>
                            <input type="number" step="0.01" name="opening_balance" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Vendor address..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4" id="btnSaveQuickVendor">
                        <i class="fas fa-save me-1"></i> Save Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const rawMaterials = @json($rawMaterials);
    const packagingMaterials = @json($packagingMaterials);

    function buildItemOptionsHtml() {
        const type = $('#purchase_type').val();
        let options = '<option value="">Select Item</option>';
        
        if (type === 'Raw Material' || type === 'Mixed') {
            if(type === 'Mixed') options += '<optgroup label="Raw Materials">';
            rawMaterials.forEach(item => {
                let unitName = item.unit ? item.unit.name : '-';
                let price = item.purchase_price || item.price || 0;
                options += `<option value="${item.id}" data-type="RawMaterial" data-unit="${unitName}" data-price="${price}">${item.name} (${item.code})</option>`;
            });
            if(type === 'Mixed') options += '</optgroup>';
        }
        
        if (type === 'Packaging' || type === 'Mixed') {
            if(type === 'Mixed') options += '<optgroup label="Packaging Materials">';
            packagingMaterials.forEach(item => {
                let unitName = item.unit ? item.unit.name : '-';
                let price = item.purchase_price || item.price || 0;
                options += `<option value="${item.id}" data-type="PackagingMaterial" data-unit="${unitName}" data-price="${price}">${item.name} (${item.code})</option>`;
            });
            if(type === 'Mixed') options += '</optgroup>';
        }

        return options;
    }

    function populateSelectOptions($targetSelects) {
        const options = buildItemOptionsHtml();
        $targetSelects.each(function() {
            const currentVal = $(this).val();
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
            }
            $(this).html(options);
            if(currentVal && $(this).find(`option[value="${currentVal}"]`).length > 0) {
                $(this).val(currentVal);
            }
            $(this).select2({ width: '100%' });
        });
    }

    function filterItems() {
        populateSelectOptions($('.item-select'));
    }

    function updateRowItemType(selectElem) {
        const $select = $(selectElem);
        const selectedOpt = $select.find('option:selected');
        const itemType = selectedOpt.data('type');
        const unit = selectedOpt.data('unit') || '-';
        const price = parseFloat(selectedOpt.data('price')) || 0;

        const $row = $select.closest('tr');
        $row.find('.row-item-type option').val(itemType);
        $row.find('.item-unit').text(unit);

        const currentItemId = $select.val();
        const prevItemId = $select.data('prev-item-id');

        if (currentItemId && currentItemId !== prevItemId) {
            $select.data('prev-item-id', currentItemId);
            const priceInput = $row.find('.item-price');
            if (price > 0) {
                priceInput.val(price.toFixed(2));
            }
            calcSubtotal(priceInput);
        }

        $select.removeClass('is-invalid-custom');
        $select.next('.select2-container').find('.select2-selection').removeClass('is-invalid-custom');
    }

    function updateRowIndexes() {
        $('#itemsBody tr.item-row').each(function(index) {
            $(this).find('.row-number').text(index + 1);
        });
        $('#itemsCountBadge').text($('#itemsBody tr.item-row').length + ' Item Row(s)');
    }

    function addRow() {
        const trHtml = `
        <tr class="item-row">
            <td class="excel-row-num row-number">1</td>
            <td>
                <select name="item_type[]" class="d-none row-item-type"><option value="RawMaterial"></option></select>
                <select name="item_id[]" class="form-control select2 item-select" required onchange="updateRowItemType(this)"></select>
            </td>
            <td><input type="number" step="0.0001" name="qty[]" class="excel-input item-qty fw-semibold text-end" placeholder="0.00" required oninput="calcSubtotal(this)"></td>
            <td><div class="excel-unit-cell"><span class="item-unit">-</span></div></td>
            <td><input type="number" step="0.01" name="unit_price[]" class="excel-input item-price fw-semibold text-end" placeholder="0.00" required oninput="calcSubtotal(this)"></td>
            <td><input type="number" step="0.01" name="discount[]" class="excel-input item-disc text-danger text-end" value="0" oninput="calcSubtotal(this)"></td>
            <td><input type="number" step="0.01" name="tax[]" class="excel-input item-tax text-info text-end" value="0" oninput="calcSubtotal(this)"></td>
            <td><input type="text" name="batch_no[]" class="excel-input" placeholder="Batch #"></td>
            <td>
                <div class="excel-date-box">
                    <input type="date" name="mfg_date[]" class="excel-date-input" title="Mfg Date">
                    <input type="date" name="exp_date[]" class="excel-date-input" title="Exp Date">
                </div>
            </td>
            <td><input type="text" class="excel-input item-subtotal excel-input-readonly text-end pe-2" readonly value="0.00"></td>
            <td>
                <button type="button" class="btn-excel-delete remove-row" title="Delete Row">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>`;
        
        const $newRow = $(trHtml);
        $('#itemsBody').append($newRow);
        
        // Populate options ONLY for the newly added row!
        populateSelectOptions($newRow.find('.item-select'));
        updateRowIndexes();
    }

    $(document).on('click', '.remove-row', function() {
        if ($('.item-row').length > 1) {
            if ($(this).closest('tr').find('.select2').hasClass("select2-hidden-accessible")) {
                $(this).closest('tr').find('.select2').select2('destroy');
            }
            $(this).closest('tr').remove();
            updateRowIndexes();
            calcTotal();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Delete Row',
                text: 'At least one item row is required for the purchase invoice.',
                confirmButtonColor: '#2563eb'
            });
        }
    });

    function calcSubtotal(elem) {
        const tr = $(elem).closest('tr');
        const qty = parseFloat(tr.find('.item-qty').val()) || 0;
        const price = parseFloat(tr.find('.item-price').val()) || 0;
        const disc = parseFloat(tr.find('.item-disc').val()) || 0;
        const tax = parseFloat(tr.find('.item-tax').val()) || 0;
        
        const sub = (qty * price) - disc + tax;
        tr.find('.item-subtotal').val(sub.toFixed(2));
        tr.find('.item-subtotal').data('raw-sub', qty * price);
        tr.find('.item-subtotal').data('raw-disc', disc);
        tr.find('.item-subtotal').data('raw-tax', tax);

        $(elem).removeClass('is-invalid-custom');
        calcTotal();
    }

    function calcTotal() {
        let grandTotal = 0;
        let totalSubtotal = 0;
        let totalDiscount = 0;
        let totalTax = 0;
        
        $('.item-subtotal').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
            totalSubtotal += parseFloat($(this).data('raw-sub')) || 0;
            totalDiscount += parseFloat($(this).data('raw-disc')) || 0;
            totalTax += parseFloat($(this).data('raw-tax')) || 0;
        });
        
        const transport = parseFloat($('#transport_charges').val()) || 0;
        grandTotal += transport;
        
        const paid = parseFloat($('#paidAmount').val()) || 0;
        const bal = grandTotal - paid;
        
        $('#lblSubtotal').text('Rs. ' + totalSubtotal.toFixed(2));
        $('#lblDiscount').text('- Rs. ' + totalDiscount.toFixed(2));
        $('#lblTax').text('+ Rs. ' + totalTax.toFixed(2));
        $('#lblTransport').text('+ Rs. ' + transport.toFixed(2));
        $('#lblTotal').text('Rs. ' + grandTotal.toFixed(2));
        $('#lblBalance').text('Rs. ' + bal.toFixed(2));
        
        if (paid === 0) $('#payment_status').val('Pending');
        else if (paid >= grandTotal) $('#payment_status').val('Paid');
        else $('#payment_status').val('Partial');
    }

    $('#payment_method').on('change', function() {
        if ($(this).val() === 'Credit') {
            $('#paidAmount').val(0).prop('readonly', true);
            $('#paymentAccountDiv').addClass('d-none');
            $('#payment_account_id').val('').trigger('change');
        } else {
            $('#paidAmount').prop('readonly', false);
            $('#paymentAccountDiv').removeClass('d-none');
        }
        calcTotal();
    });

    /* =========================================================================
       ENTER KEY NAVIGATION & NEW ROW AUTOMATIC CREATION
       ========================================================================= */
    $(document).on('keydown', '.item-row input, .item-row select', function(e) {
        if (e.which === 13 || e.keyCode === 13) {
            e.preventDefault();
            
            const $currentRow = $(this).closest('tr.item-row');
            const $nextRow = $currentRow.next('tr.item-row');

            if ($nextRow.length > 0) {
                const $nextQtyInput = $nextRow.find('.item-qty');
                if ($nextQtyInput.length) {
                    $nextQtyInput.focus();
                } else {
                    $nextRow.find('.item-select').select2('open');
                }
            } else {
                // Last row -> Add new row and focus on its item select
                addRow();
                setTimeout(function() {
                    const $lastRow = $('#itemsBody tr.item-row:last');
                    $lastRow.find('.item-select').select2('open');
                }, 100);
            }
            return false;
        }
    });

    $(document).on('click', '#btnOpenVendorModal', function(e) {
        e.preventDefault();
        $('#quickAddVendorModal').modal('show');
    });

    $(document).on('click', '[data-bs-dismiss="modal"], [data-dismiss="modal"]', function(e) {
        $('#quickAddVendorModal').modal('hide');
    });

    /* =========================================================================
       QUICK ADD VENDOR VIA AJAX MODAL
       ========================================================================= */
    $('#formQuickAddVendor').on('submit', function(e) {
        e.preventDefault();
        const vendorName = $('#vendorNameInput').val().trim();
        if (!vendorName) {
            Swal.fire({ icon: 'error', title: 'Vendor Name Required', text: 'Please enter vendor name.' });
            return false;
        }

        const btn = $('#btnSaveQuickVendor');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: "{{ route('vendors.store.ajax') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Vendor');
                if (res.ok && res.vendor) {
                    const newOpt = new Option(res.vendor.name, res.vendor.id, true, true);
                    $('#vendor_id').append(newOpt).trigger('change');
                    $('#vendor_id').removeClass('is-invalid-custom');
                    $('#vendor_id').next('.select2-container').find('.select2-selection').removeClass('is-invalid-custom');
                    
                    $('#quickAddVendorModal').modal('hide');
                    $('#formQuickAddVendor')[0].reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Vendor Added!',
                        text: `Vendor '${res.vendor.name}' added and selected successfully.`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Vendor');
                let msg = 'Failed to add vendor.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
                    }
                }
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            }
        });
    });

    /* =========================================================================
       NO-REFRESH INTERACTIVE FORM VALIDATION & AJAX SUBMISSION
       ========================================================================= */
    function clearFieldErrors() {
        $('.is-invalid-custom').removeClass('is-invalid-custom');
        $('.select2-selection').removeClass('is-invalid-custom');
        $('#ajaxErrorContainer').slideUp();
        $('#ajaxErrorList').empty().hide();
    }

    function submitFormInteractive(status) {
        clearFieldErrors();
        $('#invoice_status').val(status);

        let errors = [];

        // 1. Header Validation
        const purchaseDate = $('#purchase_date').val();
        if (!purchaseDate) {
            $('#purchase_date').addClass('is-invalid-custom');
            errors.push('Purchase Date is required.');
        }

        const vendorId = $('#vendor_id').val();
        if (!vendorId) {
            $('#vendor_id').next('.select2-container').find('.select2-selection').addClass('is-invalid-custom');
            errors.push('Please select a Vendor.');
        }

        const paymentMethod = $('#payment_method').val();
        if (paymentMethod !== 'Credit') {
            const accountId = $('#payment_account_id').val();
            if (!accountId) {
                $('#payment_account_id').next('.select2-container').find('.select2-selection').addClass('is-invalid-custom');
                errors.push('Please select a Payment Account for Cash/Bank payment.');
            }
        }

        // 2. Table Rows Validation
        let validRowsCount = 0;
        $('.item-row').each(function(idx) {
            const rowNum = idx + 1;
            const itemSelect = $(this).find('.item-select');
            const itemId = itemSelect.val();
            const qtyInput = $(this).find('.item-qty');
            const qty = parseFloat(qtyInput.val());
            const priceInput = $(this).find('.item-price');
            const price = parseFloat(priceInput.val());

            let rowHasError = false;

            if (!itemId) {
                itemSelect.next('.select2-container').find('.select2-selection').addClass('is-invalid-custom');
                rowHasError = true;
            }

            if (isNaN(qty) || qty <= 0) {
                qtyInput.addClass('is-invalid-custom');
                rowHasError = true;
            }

            if (isNaN(price) || price < 0) {
                priceInput.addClass('is-invalid-custom');
                rowHasError = true;
            }

            if (rowHasError) {
                errors.push(`Row #${rowNum}: Please select an Item and enter valid Qty (> 0) and Price.`);
            } else {
                validRowsCount++;
            }
        });

        if (validRowsCount === 0 && errors.length === 0) {
            errors.push('At least one valid Item row is required.');
        }

        // IF VALIDATION FAILS: STOP SUBMISSION, DO NOT REFRESH PAGE, KEEP ALL ENTERED DATA INTACT
        if (errors.length > 0) {
            let errorHtml = '';
            errors.forEach(err => {
                errorHtml += `<li>${err}</li>`;
            });
            $('#ajaxErrorMessage').html('Validation Failed: Please fill out all required fields marked in red.');
            $('#ajaxErrorList').html(errorHtml).show();
            $('#ajaxErrorContainer').slideDown();

            // Scroll to top of error banner smoothly
            $('html, body').animate({ scrollTop: $('#ajaxErrorContainer').offset().top - 100 }, 300);

            Swal.fire({
                icon: 'error',
                title: 'Validation Missing',
                text: 'Some required fields are empty. Please check the highlighted red fields below.',
                confirmButtonColor: '#ef4444'
            });

            return false;
        }

        // SUBMIT VIA AJAX (Page Data remains intact on any server error)
        const btnSaveDraft = $('#btnSaveDraft');
        const btnSubmitComplete = $('#btnSubmitComplete');
        
        btnSaveDraft.prop('disabled', true);
        btnSubmitComplete.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving Purchase...');

        const formData = $('#purchaseForm').serialize();

        $.ajax({
            url: $('#purchaseForm').attr('action'),
            type: 'POST',
            data: formData,
            success: function(res) {
                if (res.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.message || 'Purchase invoice saved successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = res.redirect || "{{ route('material-purchases.index') }}";
                    });
                }
            },
            error: function(xhr) {
                btnSaveDraft.prop('disabled', false);
                btnSubmitComplete.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Complete Purchase Invoice');

                let errMsg = 'An unexpected error occurred while saving.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }

                $('#ajaxErrorMessage').text(errMsg);
                $('#ajaxErrorContainer').slideDown();

                Swal.fire({
                    icon: 'error',
                    title: 'Save Failed',
                    text: errMsg,
                    confirmButtonColor: '#ef4444'
                });
            }
        });
    }

    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
        filterItems();
        updateRowIndexes();
    });
</script>
@endsection
