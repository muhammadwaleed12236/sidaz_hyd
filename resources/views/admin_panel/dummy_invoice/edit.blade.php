@extends('admin_panel.layout.app')

@section('content')
<style>
    /* Authentic Microsoft Excel Spreadsheet Theme */
    .excel-grid-container {
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .excel-grid-table {
        border-collapse: collapse !important;
        width: 100% !important;
        margin-bottom: 0 !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        background-color: #ffffff !important;
    }
    .excel-grid-table th {
        background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%) !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        letter-spacing: 0.5px;
        border: 1px solid #cbd5e1 !important;
        padding: 8px 6px !important;
        text-align: center !important;
        vertical-align: middle !important;
        user-select: none;
    }
    .excel-grid-table td {
        border: 1px solid #cbd5e1 !important;
        padding: 0 !important;
        margin: 0 !important;
        height: 36px !important;
        vertical-align: middle !important;
        background-color: #ffffff;
        position: relative;
    }
    /* Cell Focus Highlight - MS Excel 2px Green Border */
    .excel-grid-table td:focus-within,
    .excel-grid-table td.active-excel-cell {
        box-shadow: inset 0 0 0 2px #107c41 !important; /* Authentic MS Excel Green */
        background-color: #ffffff !important;
        z-index: 5;
    }
    /* Completely strip Bootstrap input borders/paddings inside cells */
    .excel-grid-table input,
    .excel-grid-table select,
    .excel-grid-table .excel-cell-input,
    .excel-grid-table .form-control {
        display: block !important;
        width: 100% !important;
        height: 36px !important;
        margin: 0 !important;
        padding: 4px 8px !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        outline: none !important;
        background: transparent !important;
        font-size: 13px !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        color: #0f172a !important;
        box-sizing: border-box !important;
    }
    .excel-grid-table input:focus,
    .excel-grid-table select:focus,
    .excel-grid-table .excel-cell-input:focus,
    .excel-grid-table .form-control:focus {
        background-color: #ffffff !important;
        outline: none !important;
        box-shadow: none !important;
    }
    /* Computed Total Cell */
    .excel-grid-table input.total-input {
        background-color: #f8fafc !important;
        color: #107c41 !important;
        font-weight: 700 !important;
        font-family: 'Consolas', 'Courier New', monospace !important;
    }
    /* Excel Row Header (# Column) */
    .excel-row-num {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        text-align: center !important;
        vertical-align: middle !important;
        border: 1px solid #cbd5e1 !important;
        user-select: none;
        width: 35px;
    }
    .excel-grid-table tbody tr:hover td {
        background-color: #f8fafc;
    }
    .excel-grid-table tbody tr:hover td.excel-row-num {
        background-color: #e2e8f0 !important;
        color: #107c41 !important;
    }
    /* Excel Summary Footer */
    .excel-grid-table tfoot td {
        background-color: #f1f5f9 !important;
        border-top: 2px solid #107c41 !important;
        border-bottom: 1px solid #cbd5e1 !important;
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid py-4">
            <div class="row mb-3 align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="fas fa-edit text-primary"></i> Edit Warranty Sale Invoice #{{ $invoice->invoice_no }}
                    </h4>
                    <p class="text-muted small mb-0">Modify standalone warranty sale invoice details.</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('dummy-invoices.index') }}" class="btn btn-outline-secondary px-3 fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Back to Invoices
                    </a>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('dummy-invoices.update', $invoice->id) }}" method="POST" id="dummyInvoiceForm">
                @csrf
                @method('PUT')
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Invoice & Customer Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Invoice # <span class="text-danger">*</span></label>
                                <input type="text" name="invoice_no" class="form-control fw-bold font-monospace" value="{{ old('invoice_no', $invoice->invoice_no) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Invoice Date <span class="text-danger">*</span></label>
                                <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $invoice->invoice_date) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Order Date</label>
                                <input type="date" name="order_date" class="form-control" value="{{ old('order_date', $invoice->order_date) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Gate Pass #</label>
                                <input type="text" name="gate_pass_no" class="form-control" value="{{ old('gate_pass_no', $invoice->gate_pass_no) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control fw-bold" value="{{ old('customer_name', $invoice->customer_name) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Licence No.</label>
                                <input type="text" name="licence_no" class="form-control" value="{{ old('licence_no', $invoice->licence_no) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Licence Expiry</label>
                                <input type="text" name="licence_expiry" class="form-control" value="{{ old('licence_expiry', $invoice->licence_expiry) }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Customer Address</label>
                                <textarea name="customer_address" class="form-control" rows="2">{{ old('customer_address', $invoice->customer_address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Items Excel Grid Table -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-table me-2 text-success"></i>Product Grid (Excel Spreadsheet Mode)</h6>
                            <small class="text-muted">Use <strong>Tab / Enter</strong> to move next, <strong>Arrow Keys</strong> to navigate rows, auto-add row on Enter.</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-success fw-bold px-3" id="addRowBtn">
                            <i class="fas fa-plus me-1"></i> Add Row
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table excel-grid-table mb-0" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 35px;">#</th>
                                        <th style="min-width: 220px;">PRODUCT NAME</th>
                                        <th style="width: 100px;">PACK</th>
                                        <th style="width: 120px;">BATCH NO</th>
                                        <th style="width: 95px;">MFG. DATE</th>
                                        <th style="width: 95px;">EXP. DATE</th>
                                        <th style="width: 85px;">QTY</th>
                                        <th style="width: 95px;">MRP</th>
                                        <th style="width: 95px;">T.P.</th>
                                        <th style="width: 100px;">D.P.</th>
                                        <th style="width: 120px;">TOTAL</th>
                                        <th style="width: 45px;">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsContainer">
                                    <!-- Existing rows dynamically rendered -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="10" class="text-end fw-bold fs-6 py-2 pe-3 bg-light">Grand Total Amount:</td>
                                        <td class="text-end fw-bold fs-6 text-success font-monospace py-2 pe-2 bg-light" id="grandTotalDisplay">Rs. 0.00</td>
                                        <td class="bg-light"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Warranty & Signatures block -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-certificate me-2 text-primary"></i>Warranty Certificate & Signatures</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">DRAP Act Warranty Statement</label>
                                <textarea name="warranty_text" class="form-control small" rows="3">{{ old('warranty_text', $invoice->warranty_text) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label class="form-label fw-bold">Signatory Name</label>
                                    <input type="text" name="signatory_name" class="form-control" value="{{ old('signatory_name', $invoice->signatory_name) }}">
                                </div>
                                <div>
                                    <label class="form-label fw-bold">Signatory Title</label>
                                    <input type="text" name="signatory_title" class="form-control" value="{{ old('signatory_title', $invoice->signatory_title) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mb-5">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm me-2">
                        <i class="fas fa-save me-2"></i> Update Invoice
                    </button>
                    <a href="{{ route('dummy-invoices.print', $invoice->id) }}" target="_blank" class="btn btn-success px-4 py-2 fw-bold shadow-sm">
                        <i class="fas fa-print me-2"></i> Print Invoice
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const systemProducts = @json($products);
    const existingItems = @json($invoice->items);
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowIndex = 0;

    function addRow(data = {}) {
        rowIndex++;
        const container = document.getElementById('itemsContainer');
        const tr = document.createElement('tr');
        tr.id = `row_${rowIndex}`;

        let datalistOptions = '';
        systemProducts.forEach(p => {
            datalistOptions += `<option value="${p.item_name}"></option>`;
        });

        tr.innerHTML = `
            <td class="excel-row-num row-number">${rowIndex}</td>
            <td>
                <datalist id="productList_${rowIndex}">
                    ${datalistOptions}
                </datalist>
                <input type="text" name="items[${rowIndex}][product_name]" class="excel-cell-input fw-bold product-name-input grid-nav" placeholder="Type / Search Product..." list="productList_${rowIndex}" value="${data.product_name || ''}" oninput="onProductNameInput(this, ${rowIndex})" autocomplete="off" required>
                <input type="hidden" name="items[${rowIndex}][product_id]" class="product-id-input" value="${data.product_id || ''}">
            </td>
            <td><input type="text" name="items[${rowIndex}][pack]" class="excel-cell-input text-center grid-nav" placeholder="Pack (e.g. 30 ML)" value="${data.pack || ''}"></td>
            <td><input type="text" name="items[${rowIndex}][batch_no]" class="excel-cell-input text-center fw-bold text-primary font-monospace grid-nav" placeholder="Batch #" value="${data.batch_no || ''}"></td>
            <td><input type="text" name="items[${rowIndex}][mfg_date]" class="excel-cell-input text-center grid-nav" placeholder="MM-YY" value="${data.mfg_date || ''}"></td>
            <td><input type="text" name="items[${rowIndex}][exp_date]" class="excel-cell-input text-center grid-nav" placeholder="MM-YY" value="${data.exp_date || ''}"></td>
            <td><input type="number" step="any" name="items[${rowIndex}][qty]" class="excel-cell-input text-center qty-input font-monospace grid-nav" placeholder="Qty" value="${data.qty || ''}" min="0.01" oninput="calcRow(${rowIndex})" required></td>
            <td><input type="number" step="any" name="items[${rowIndex}][mrp]" class="excel-cell-input text-end mrp-input font-monospace grid-nav" placeholder="0.00" value="${data.mrp || ''}" oninput="calcRow(${rowIndex})"></td>
            <td><input type="number" step="any" name="items[${rowIndex}][tp]" class="excel-cell-input text-end tp-input font-monospace grid-nav" placeholder="0.00" value="${data.tp || ''}" oninput="calcRow(${rowIndex})"></td>
            <td><input type="number" step="any" name="items[${rowIndex}][dp]" class="excel-cell-input text-end dp-input fw-bold font-monospace grid-nav" placeholder="0.00" value="${data.dp || ''}" oninput="calcRow(${rowIndex})" required></td>
            <td><input type="number" step="any" name="items[${rowIndex}][total_amount]" class="excel-cell-input text-end total-input" placeholder="0.00" value="${data.total_amount || 0}" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" onclick="removeRow(${rowIndex})" title="Delete Row">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        container.appendChild(tr);
        calcRow(rowIndex);
        updateRowNumbers();
    }

    window.onProductNameInput = function(inputEl, rIndex) {
        const tr = document.getElementById(`row_${rIndex}`);
        if (!tr) return;
        const val = inputEl.value.trim().toLowerCase();
        const matched = systemProducts.find(p => p.item_name.toLowerCase() === val);
        if (matched) {
            tr.querySelector('.product-id-input').value = matched.id;
            tr.querySelector('.mrp-input').value = matched.mrp || '';
            tr.querySelector('.tp-input').value = matched.tp || '';
            tr.querySelector('.dp-input').value = matched.dp || '';
        } else {
            tr.querySelector('.product-id-input').value = '';
        }
        calcRow(rIndex);
    };

    window.calcRow = function(rIndex) {
        const tr = document.getElementById(`row_${rIndex}`);
        if (!tr) return;
        const qty = parseFloat(tr.querySelector('.qty-input').value) || 0;
        const dp = parseFloat(tr.querySelector('.dp-input').value) || 0;
        const total = qty * dp;
        tr.querySelector('.total-input').value = total > 0 ? total.toFixed(2) : '0.00';
        calcGrandTotal();
    };

    window.calcGrandTotal = function() {
        let total = 0;
        document.querySelectorAll('.total-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('grandTotalDisplay').innerText = 'Rs. ' + total.toLocaleString('en-PK', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    window.removeRow = function(rIndex) {
        const tr = document.getElementById(`row_${rIndex}`);
        if (tr) {
            tr.remove();
            calcGrandTotal();
            updateRowNumbers();
        }
    };

    function updateRowNumbers() {
        document.querySelectorAll('#itemsContainer tr').forEach((tr, index) => {
            tr.querySelector('.row-number').innerText = index + 1;
        });
    }

    document.getElementById('addRowBtn').addEventListener('click', function () {
        addRow();
        setTimeout(() => {
            const inputs = document.querySelectorAll('#itemsContainer .product-name-input');
            if (inputs.length > 0) inputs[inputs.length - 1].focus();
        }, 50);
    });

    // Excel Keyboard Grid Navigation (Enter, Tab, Arrow Up/Down)
    document.addEventListener('keydown', function(e) {
        const target = e.target;
        if (!target.classList.contains('grid-nav')) return;

        const navInputs = Array.from(document.querySelectorAll('#itemsContainer .grid-nav'));
        const currentIndex = navInputs.indexOf(target);
        if (currentIndex === -1) return;

        if (e.key === 'Enter' || (e.key === 'Tab' && !e.shiftKey)) {
            if (currentIndex === navInputs.length - 1) {
                e.preventDefault();
                addRow();
                setTimeout(() => {
                    const updatedInputs = Array.from(document.querySelectorAll('#itemsContainer .grid-nav'));
                    if (updatedInputs[currentIndex + 1]) {
                        updatedInputs[currentIndex + 1].focus();
                    }
                }, 50);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                navInputs[currentIndex + 1].focus();
            }
        } else if (e.key === 'ArrowDown') {
            const rowInputs = Array.from(target.closest('tr').querySelectorAll('.grid-nav'));
            const colIndex = rowInputs.indexOf(target);
            const nextRow = target.closest('tr').nextElementSibling;
            if (nextRow) {
                e.preventDefault();
                const nextRowInputs = Array.from(nextRow.querySelectorAll('.grid-nav'));
                if (nextRowInputs[colIndex]) {
                    nextRowInputs[colIndex].focus();
                }
            }
        } else if (e.key === 'ArrowUp') {
            const rowInputs = Array.from(target.closest('tr').querySelectorAll('.grid-nav'));
            const colIndex = rowInputs.indexOf(target);
            const prevRow = target.closest('tr').previousElementSibling;
            if (prevRow) {
                e.preventDefault();
                const prevRowInputs = Array.from(prevRow.querySelectorAll('.grid-nav'));
                if (prevRowInputs[colIndex]) {
                    prevRowInputs[colIndex].focus();
                }
            }
        }
    });

    if (existingItems && existingItems.length > 0) {
        existingItems.forEach(item => addRow(item));
    } else {
        addRow();
    }
});
</script>
@endsection
