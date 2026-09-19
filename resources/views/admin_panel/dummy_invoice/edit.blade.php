@extends('admin_panel.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-primary mb-1">
                <i class="fas fa-edit me-2"></i>Edit Warranty Sale Invoice #{{ $invoice->invoice_no }}
            </h3>
            <p class="text-muted small mb-0">Modify standalone warranty sale invoice details.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('dummy-invoices.index') }}" class="btn btn-outline-secondary px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Invoices
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                        <input type="text" name="invoice_no" class="form-control fw-bold" value="{{ old('invoice_no', $invoice->invoice_no) }}" required>
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

        <!-- Product Items Table -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-boxes me-2 text-primary"></i>Invoice Products</h6>
                <button type="button" class="btn btn-sm btn-success fw-bold" id="addRowBtn">
                    <i class="fas fa-plus me-1"></i> Add Product Row
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" id="itemsTable">
                        <thead class="bg-light text-center fs-7 text-uppercase fw-bold">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th style="min-width: 220px;">Product Name</th>
                                <th style="width: 100px;">Pack</th>
                                <th style="width: 120px;">Batch No</th>
                                <th style="width: 100px;">Mfg. Date</th>
                                <th style="width: 100px;">Exp. Date</th>
                                <th style="width: 90px;">Qty</th>
                                <th style="width: 90px;">MRP</th>
                                <th style="width: 90px;">T.P.</th>
                                <th style="width: 100px;">D.P.</th>
                                <th style="width: 120px;">Total</th>
                                <th style="width: 50px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsContainer">
                            <!-- Existing rows will be rendered via JS -->
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="10" class="text-end fw-bold fs-6">Grand Total Amount:</td>
                                <td class="text-end fw-bold fs-6 text-primary" id="grandTotalDisplay">Rs. 0.00</td>
                                <td></td>
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
            <td class="text-center row-number fw-bold text-muted">${rowIndex}</td>
            <td>
                <datalist id="productList_${rowIndex}">
                    ${datalistOptions}
                </datalist>
                <input type="text" name="items[${rowIndex}][product_name]" class="form-control form-control-sm fw-bold product-name-input" placeholder="Type / Search Product..." list="productList_${rowIndex}" value="${data.product_name || ''}" oninput="onProductNameInput(this, ${rowIndex})" autocomplete="off" required>
                <input type="hidden" name="items[${rowIndex}][product_id]" class="product-id-input" value="${data.product_id || ''}">
            </td>
            <td><input type="text" name="items[${rowIndex}][pack]" class="form-control form-control-sm text-center" placeholder="Pack (e.g. 30 ML)" value="${data.pack || ''}"></td>
            <td><input type="text" name="items[${rowIndex}][batch_no]" class="form-control form-control-sm text-center fw-bold text-primary" placeholder="Batch #" value="${data.batch_no || ''}"></td>
            <td><input type="text" name="items[${rowIndex}][mfg_date]" class="form-control form-control-sm text-center" placeholder="MM-YY" value="${data.mfg_date || ''}"></td>
            <td><input type="text" name="items[${rowIndex}][exp_date]" class="form-control form-control-sm text-center" placeholder="MM-YY" value="${data.exp_date || ''}"></td>
            <td><input type="number" step="any" name="items[${rowIndex}][qty]" class="form-control form-control-sm text-center qty-input" placeholder="Qty" value="${data.qty || ''}" min="0.01" oninput="calcRow(${rowIndex})" required></td>
            <td><input type="number" step="any" name="items[${rowIndex}][mrp]" class="form-control form-control-sm text-end mrp-input" placeholder="0.00" value="${data.mrp || ''}" oninput="calcRow(${rowIndex})"></td>
            <td><input type="number" step="any" name="items[${rowIndex}][tp]" class="form-control form-control-sm text-end tp-input" placeholder="0.00" value="${data.tp || ''}" oninput="calcRow(${rowIndex})"></td>
            <td><input type="number" step="any" name="items[${rowIndex}][dp]" class="form-control form-control-sm text-end dp-input fw-bold" placeholder="0.00" value="${data.dp || ''}" oninput="calcRow(${rowIndex})" required></td>
            <td><input type="number" step="any" name="items[${rowIndex}][total_amount]" class="form-control form-control-sm text-end total-input fw-bold text-success" placeholder="0.00" value="${data.total_amount || 0}" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeRow(${rowIndex})">
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
        tr.querySelector('.total-input').value = total.toFixed(2);
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
    });

    if (existingItems && existingItems.length > 0) {
        existingItems.forEach(item => addRow(item));
    } else {
        addRow();
    }
});
</script>
@endsection
