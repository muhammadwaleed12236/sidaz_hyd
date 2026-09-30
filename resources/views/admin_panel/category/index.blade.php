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
    .btn-hdr-primary { background: var(--erp-primary); color: #fff; border-color: var(--erp-primary); box-shadow: 0 1px 3px rgba(79,70,229,0.2); }
    .btn-hdr-primary:hover { background: #4338ca; border-color: #4338ca; color: #fff; }

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

    /* ID Badge */
    .cat-id-badge { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; font-family: monospace; }

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

    /* Mobile Cards View */
    .mobile-cat-cards { display: none; padding: 10px; }
    @media (max-width: 768px) {
        .erp-page { padding: 8px 0; }
        .erp-card-header { flex-direction: column; align-items: flex-start; gap: 8px; padding: 10px 12px; }
        .erp-hdr-actions { width: 100%; }
        .btn-hdr { width: 100%; justify-content: center; height: 34px; font-size: 0.8rem; }
        .erp-table-wrap { display: none !important; }
        .mobile-cat-cards { display: flex; flex-direction: column; gap: 8px; }
    }

    .cat-mcard {
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: 8px;
        padding: 10px 12px;
        box-shadow: 0 1px 3px rgba(15,23,42,0.03);
    }
    .cat-mcard-hdr { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .cat-mcard-title { font-weight: 700; font-size: 0.9rem; color: #0f172a; margin-top: 4px; }
    .cat-mcard-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 6px !important;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
        width: 100%;
    }
    .cat-mcard-actions .btn-act { width: 100% !important; justify-content: center !important; height: 32px !important; font-size: 0.76rem !important; border-radius: 6px !important; }
</style>

<div class="erp-page">
    <div class="container-fluid px-2">
        <div class="erp-card">
            
            {{-- Header Bar --}}
            <div class="erp-card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h1 class="page-head-title"><i class="fas fa-tags text-primary me-1"></i> Categories</h1>
                    <div class="head-kpis">
                        <span class="kpi-pill">Total: <strong>{{ count($category) }}</strong></span>
                    </div>
                </div>
                <div class="erp-hdr-actions">
                    @can('categories.create')
                        <button type="button" class="btn-hdr btn-hdr-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Desktop Table View --}}
            <div class="erp-table-wrap">
                <table id="default-datatable" class="table align-middle nowrap">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">ID</th>
                            <th>Category Name</th>
                            <th class="text-center" style="width: 130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category as $company)
                            <tr class="cat-row-item">
                                <td class="text-center id"><span class="cat-id-badge">#{{ $company->id }}</span></td>
                                <td class="name fw-semibold text-dark">{{ $company->name }}</td>
                                <td class="text-center">
                                    @include('admin_panel.partials.action_buttons', [
                                        'editRoute' => route('store.category'),
                                        'deleteRoute' => route('delete.category', $company->id),
                                        'editIsLink' => false,
                                        'permissions' => [
                                            'edit' => 'categories.edit',
                                            'delete' => 'categories.delete',
                                        ],
                                        'deleteMsg' => 'Are you sure you want to delete this category?',
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>{{-- /erp-table-wrap --}}

            {{-- Mobile Cards View (< 768px) --}}
            <div class="mobile-cat-cards">
                @foreach ($category as $company)
                    <div class="cat-mcard cat-row-item">
                        <div class="cat-mcard-hdr">
                            <span class="cat-id-badge">#{{ $company->id }}</span>
                            <span class="badge bg-light text-secondary border">Category</span>
                        </div>
                        <div class="cat-mcard-title name">{{ $company->name }}</div>
                        <div class="cat-mcard-actions">
                            @can('categories.edit')
                                <button type="button" class="btn-act btn-act-edit edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            @endcan
                            @can('categories.delete')
                                <form action="{{ route('delete.category', $company->id) }}" method="GET" class="d-inline w-100" onsubmit="return confirm('Are you sure you want to delete this category?')">
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

{{-- Add/Edit Category Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 10px;">
            <div class="modal-header bg-light px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark fs-6" id="exampleModalLabel"><span id="modalTitleText">Add Category</span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="myform" action="{{ route('store.category', [], false) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark small d-block mb-1">Category Title <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm px-3 py-2 w-100" id="name" placeholder="Enter category title..." required style="border-radius: 6px; border: 1px solid #cbd5e1;" />
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-2 border-top">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-dismiss="modal">Close</button>
                    @canany(['categories.create', 'categories.edit'])
                        <button type="submit" class="btn btn-sm btn-primary px-3 save-btn">
                            Save Category
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
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    $(document).on('submit', '.myform', function(e) {
        e.preventDefault();
        var form = this;
        var formdata = new FormData(form);
        var url = $(form).attr('action');
        try {
            if (url && (url.indexOf('http://') === 0 || url.indexOf('https://') === 0)) {
                var u = new URL(url);
                url = u.pathname + u.search;
            }
        } catch(err) {}
        var method = $(form).attr('method') || 'POST';
        $(form).find(':submit, .save-btn').prop('disabled', true);
        myAjax(url, formdata, method, null, { form: form });
    });

    $(document).on('click', '.edit-btn', function() {
        var item = $(this).closest(".cat-row-item");
        var id = item.find(".id, .cat-id-badge").text().replace('#', '').trim();
        var name = item.find(".name").text().trim();
        $('#id').val(id);
        $('#name').val(name);
        $('#modalTitleText').text('Edit Category');
        $("#exampleModal").modal("show");
    });

    $('#reset').on('click', function() {
        $('#id').val('');
        $('#name').val('');
        $('#modalTitleText').text('Add Category');
    });

    $(document).ready(function() {
        if($('#default-datatable').length) {
            $('#default-datatable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "order": [[0, 'desc']],
                "language": {
                    "search": "Search Category:",
                    "lengthMenu": "Show _MENU_ entries"
                }
            });
        }
    });
</script>
@endsection
