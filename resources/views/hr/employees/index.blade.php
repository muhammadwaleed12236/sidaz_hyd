@extends('admin_panel.layout.app')

@section('content')
    <!-- Script for Face API -->
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>

    @include('hr.partials.hr-styles')

    <style>
        /* Modern Employee Modal Specific Styling */
        #employeeModal .modal-dialog {
            max-width: 860px;
            margin: 1.75rem auto;
        }

        #employeeModal .modal-content {
            border: none;
            border-radius: 18px;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.25);
            overflow: hidden;
            background: #ffffff;
        }

        #employeeModal .mo dal-header.gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 22px 28px;
            border-bottom: none;
            position: relative;
        }

        #employeeModal .modal-title-wrapper {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        #employeeModal .modal-title-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #employeeModal .modal-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        #employeeModal .modal-subtitle {
            font-size: 0.83rem;
            color: rgba(255, 255, 255, 0.85);
            margin: 4px 0 0 0;
            font-weight: 400;
        }

        #employeeModal .modal-close-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        #employeeModal .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: rotate(90deg) scale(1.05);
        }

        #employeeModal .modal-body {
            padding: 24px 28px;
            max-height: calc(85vh - 140px);
            overflow-y: auto;
            background: #fdfdfd;
        }

        /* Section Banners */
        .form-section-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.83rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4338ca;
            background: linear-gradient(90deg, #eef2ff 0%, #f8fafc 100%);
            padding: 8px 16px;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
            margin-top: 6px;
            margin-bottom: 14px;
        }

        .form-section-banner i {
            font-size: 0.95rem;
            color: #4f46e5;
        }

        .form-card-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px 4px;
            margin-bottom: 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        /* Input Controls */
        .form-group-modern {
            margin-bottom: 15px;
        }

        .form-group-modern .form-label {
            font-size: 0.84rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group-modern .form-label i {
            color: #6366f1;
            font-size: 0.82rem;
        }

        .form-group-modern .required-star {
            color: #ef4444;
            font-weight: 700;
            margin-left: 2px;
        }

        .form-group-modern .form-control,
        .form-group-modern .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            padding: 9px 14px;
            font-size: 0.9rem;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .form-group-modern .form-control:focus,
        .form-group-modern .form-select:focus {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
            outline: none;
        }

        .form-group-modern .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .form-group-modern .input-group .toggle-password {
            border: 1.5px solid #e2e8f0;
            border-left: none;
            background: #f8fafc;
            color: #64748b;
            border-top-right-radius: 9px;
            border-bottom-right-radius: 9px;
            padding: 0 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group-modern .input-group .toggle-password:hover {
            background: #f1f5f9;
            color: #4f46e5;
        }

        /* Custom Time Box */
        #custom_time_container {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 10px;
            padding: 14px 16px 2px;
            margin-bottom: 15px;
        }

        /* Modern Toggle Switch Box */
        .modern-switch-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 18px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .modern-switch-box:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
        }

        .switch-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .modern-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            margin: 0;
        }

        .modern-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .3s;
            border-radius: 24px;
        }

        .switch-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        }

        .modern-switch input:checked + .switch-slider {
            background-color: #4f46e5;
        }

        .modern-switch input:checked + .switch-slider:before {
            transform: translateX(20px);
        }

        /* Document Upload Tile */
        .doc-upload-tile {
            background: #ffffff;
            border: 1.5px dashed #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .doc-upload-tile:hover {
            border-color: #6366f1;
            background: #f8fafc;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.08);
        }

        .doc-upload-tile .doc-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .doc-upload-tile .doc-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .doc-upload-tile .doc-name i {
            color: #4f46e5;
        }

        .doc-upload-tile input[type="file"] {
            font-size: 0.8rem;
            padding: 6px 10px;
            border-radius: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            width: 100%;
        }

        /* Modal Footer */
        #employeeModal .modal-footer-modern {
            padding: 16px 28px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        #employeeModal .btn-cancel {
            background: #ffffff;
            color: #64748b;
            border: 1.5px solid #cbd5e1;
            padding: 10px 22px;
            border-radius: 9px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        #employeeModal .btn-cancel:hover {
            background: #f1f5f9;
            color: #334155;
            border-color: #94a3b8;
        }

        #employeeModal .btn-save {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            padding: 10px 28px;
            border-radius: 9px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        #employeeModal .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            color: #ffffff;
        }

        /* Modern Filter Bar in Employee Directory */
        .emp-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            padding: 16px 22px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        .emp-search-container {
            position: relative;
            min-width: 260px;
            flex: 1;
            max-width: 380px;
        }

        .emp-search-container i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .emp-search-input {
            width: 100%;
            height: 42px;
            padding: 8px 14px 8px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.88rem;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .emp-search-input:focus {
            border-color: #6366f1;
            background: #ffffff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .emp-filter-select {
            height: 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 0.86rem;
            font-weight: 500;
            color: #334155;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none;
            min-width: 155px;
        }

        .emp-filter-select:focus {
            border-color: #6366f1;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .emp-segmented-group {
            display: inline-flex;
            align-items: center;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            padding: 3px;
            gap: 2px;
            height: 42px;
        }

        .emp-seg-btn {
            border: none;
            background: transparent;
            color: #64748b;
            padding: 0 12px;
            height: 34px;
            border-radius: 7px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .emp-seg-btn:hover {
            color: #4f46e5;
            background: #ffffff;
        }

        .emp-seg-btn.active {
            background: #4f46e5;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
        }

        .emp-refresh-btn {
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .emp-refresh-btn:hover {
            border-color: #6366f1;
            color: #4f46e5;
            background: #ffffff;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title"><i class="fa fa-users"></i> Employee Management</h1>
                        <p class="page-subtitle">Manage your organization's employee database, shifts, and credentials</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @can('hr.attendance.view')
                            <a href="{{ route('hr.attendance.ledger') }}" class="btn btn-outline-primary font-weight-bold px-3 py-2">
                                <i class="fa fa-book-open me-1"></i> Attendance Ledger
                            </a>
                        @endcan
                        @can('hr.employees.create')
                            <button type="button" class="btn btn-create btn-primary px-4 py-2" id="createBtn"
                                data-toggle="modal" data-target="#employeeModal"
                                data-bs-toggle="modal" data-bs-target="#employeeModal">
                                <i class="fa fa-user-plus me-1"></i> Add Employee
                            </button>
                        @endcan
                    </div>
                </div>

                <!-- Stats Row -->
                @php
                    $activeCount = $employees->where('status', 'active')->count();
                    $nonActiveCount = $employees->where('status', 'non-active')->count();
                    $terminatedCount = $employees->where('status', 'terminated')->count();
                @endphp
                <div class="stats-row">
                    <div class="stat-card primary">
                        <div class="stat-icon"><i class="fa fa-users"></i></div>
                        <div class="stat-value">{{ $employees->total() }}</div>
                        <div class="stat-label">Total Employees</div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-icon"><i class="fa fa-user-check"></i></div>
                        <div class="stat-value">{{ $activeCount }}</div>
                        <div class="stat-label">Active</div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-icon"><i class="fa fa-user-clock"></i></div>
                        <div class="stat-value">{{ $nonActiveCount }}</div>
                        <div class="stat-label">Non-Active</div>
                    </div>
                    <div class="stat-card danger">
                        <div class="stat-icon"><i class="fa fa-user-times"></i></div>
                        <div class="stat-value">{{ $terminatedCount }}</div>
                        <div class="stat-label">Terminated</div>
                    </div>
                </div>

                <!-- Employees Card -->
                <div class="hr-card">
                    <div class="emp-toolbar">
                        <div class="d-flex align-items-center gap-2 flex-wrap" style="flex: 1;">
                            <!-- Search -->
                            <div class="emp-search-container">
                                <i class="fa fa-search"></i>
                                <input type="search" id="empSearch" class="emp-search-input" placeholder="Search by name, email, department...">
                            </div>

                            <!-- Department Filter -->
                            <select id="deptFilter" class="emp-filter-select">
                                <option value="all">🏢 All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ strtolower($dept->name) }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>

                            <!-- Designation Filter -->
                            <select id="desigFilter" class="emp-filter-select">
                                <option value="all">💼 All Designations</option>
                                @foreach ($designations as $des)
                                    <option value="{{ strtolower($des->name) }}">{{ $des->name }}</option>
                                @endforeach
                            </select>

                            <!-- Status Filter -->
                            <select id="statusFilter" class="emp-filter-select" style="min-width: 130px;">
                                <option value="all">⚡ All Status</option>
                                <option value="active">Active</option>
                                <option value="non-active">Non-Active</option>
                                <option value="terminated">Terminated</option>
                            </select>

                            <!-- Timing Toggles -->
                            <div class="emp-segmented-group">
                                <button type="button" class="emp-seg-btn active" data-filter="all" title="Show All Employees">
                                    <i class="fa fa-users"></i> All
                                </button>
                                <button type="button" class="emp-seg-btn" data-filter="custom_time" title="Show Custom Timings">
                                    <i class="fa fa-clock text-info"></i> Custom
                                </button>
                                <button type="button" class="emp-seg-btn" data-filter="default_shift" title="Show Default Shift">
                                    <i class="fa fa-business-time text-warning"></i> Default
                                </button>
                            </div>

                            <!-- Refresh -->
                            <button type="button" class="emp-refresh-btn" id="refreshBtn" title="Refresh list">
                                <i class="fa fa-sync-alt"></i>
                            </button>
                        </div>

                        <!-- Count Badge -->
                        <span class="badge bg-light text-dark border px-3 py-2 font-weight-bold" id="empCount" style="font-size: 0.85rem; border-radius: 9px;">
                            {{ $employees->total() }} Employees
                        </span>
                    </div>

                    <div class="hr-grid" id="empGrid">
                        @forelse($employees as $emp)
                            <div class="hr-item-card" data-id="{{ $emp->id }}"
                                data-name="{{ strtolower($emp->first_name . ' ' . $emp->last_name) }}"
                                data-email="{{ strtolower(str_contains($emp->email, '@system.local') ? '' : $emp->email) }}"
                                data-dept="{{ strtolower($emp->department->name ?? '') }}"
                                data-desig="{{ strtolower($emp->designation->name ?? '') }}"
                                data-status="{{ strtolower($emp->status) }}">
                                <div class="hr-item-header">
                                    <div class="d-flex align-items-center">
                                        <div class="hr-avatar">
                                            {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1)) }}
                                        </div>
                                        <div class="hr-item-info">
                                            <h4 class="hr-item-name">{{ $emp->first_name }} {{ $emp->last_name }}</h4>
                                            <div class="hr-item-subtitle">
                                                @if (!str_contains($emp->email, '@system.local'))
                                                    <i class="fa fa-envelope me-1"></i>{{ $emp->email }}
                                                @elseif($emp->phone)
                                                    <i class="fa fa-phone me-1"></i>{{ $emp->phone }}
                                                @else
                                                    <span class="text-muted">No email assigned</span>
                                                @endif
                                            </div>
                                            <div class="hr-item-meta">
                                                ID: #{{ $emp->id }} • Joined
                                                {{ $emp->joining_date ? \Carbon\Carbon::parse($emp->joining_date)->format('d/m/Y') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-actions">
                                        <button type="button" class="btn btn-info btn-sm view-monthly-detail-btn" data-id="{{ $emp->id }}"
                                            data-name="{{ $emp->first_name }} {{ $emp->last_name }}" title="View Monthly Attendance & Payroll Report">
                                            <i class="fa fa-calendar-alt me-1"></i> Detail
                                        </button>
                                        @can('hr.employees.edit')
                                            <button class="btn btn-success btn-sm register-face-btn" data-id="{{ $emp->id }}"
                                                data-name="{{ $emp->first_name }} {{ $emp->last_name }}" title="Register Face ID">
                                                <i class="fa fa-camera"></i>
                                            </button>
                                            <button class="btn btn-edit btn-sm edit-btn" title="Edit Employee">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                        @endcan
                                        @can('hr.employees.delete')
                                            <button class="btn btn-delete btn-sm delete-btn"
                                                data-url="{{ route('hr.employees.destroy', $emp->id) }}" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                                <div class="hr-tags">
                                    <span class="hr-tag default mb-1"><i
                                            class="fa fa-building me-1"></i>{{ $emp->department->name ?? 'N/A' }}</span>
                                    <span class="hr-tag default mb-1"><i
                                            class="fa fa-briefcase me-1"></i>{{ $emp->designation->name ?? 'N/A' }}</span>
                                    @if ($emp->custom_start_time)
                                        <span class="hr-tag warning mb-1"><i class="fa fa-clock me-1"></i>Custom Timing</span>
                                    @else
                                        <span class="hr-tag info mb-1"><i
                                                class="fa fa-clock me-1"></i>{{ $emp->shift->name ?? 'Default' }}</span>
                                    @endif
                                    <span
                                        class="hr-tag {{ $emp->status == 'active' ? 'success' : ($emp->status == 'non-active' ? 'warning' : 'danger') }} mb-1">
                                        {{ ucfirst($emp->status) }}
                                    </span>

                                    <!-- Portal User Badge -->
                                    @if ($emp->user_id)
                                        <span class="badge bg-primary text-white p-1 mb-1"><i class="fa fa-key me-1"></i>Portal User</span>
                                    @else
                                        <span class="badge bg-light text-muted border p-1 mb-1"><i class="fa fa-user-slash me-1"></i>No Portal</span>
                                    @endif

                                    <!-- Face ID Badge -->
                                    @if (!empty($emp->face_encoding) && is_array($emp->face_encoding) && count($emp->face_encoding) > 0)
                                        <span class="badge bg-info text-white p-1 mb-1"><i class="fa fa-smile me-1"></i>Face ID</span>
                                    @endif
                                </div>

                                <!-- Hidden fields for edit -->
                                <input type="hidden" class="first_name" value="{{ $emp->first_name }}">
                                <input type="hidden" class="last_name" value="{{ $emp->last_name }}">
                                <input type="hidden" class="email" value="{{ str_contains($emp->email, '@system.local') ? '' : $emp->email }}">
                                <input type="hidden" class="has_portal_access" value="{{ $emp->user_id ? '1' : '0' }}">
                                <input type="hidden" class="phone" value="{{ $emp->phone }}">
                                <input type="hidden" class="address" value="{{ $emp->address }}">
                                <input type="hidden" class="department_id" value="{{ $emp->department_id }}">
                                <input type="hidden" class="designation_id" value="{{ $emp->designation_id }}">
                                <input type="hidden" class="shift_id" value="{{ $emp->shift_id }}">
                                <input type="hidden" class="custom_start_time" value="{{ $emp->custom_start_time }}">
                                <input type="hidden" class="custom_end_time" value="{{ $emp->custom_end_time }}">
                                <input type="hidden" class="joining_date" value="{{ $emp->joining_date }}">
                                <input type="hidden" class="status" value="{{ $emp->status }}">
                                <input type="hidden" class="is_docs_submitted" value="{{ $emp->is_docs_submitted }}">
                                <input type="hidden" class="doc_degree" value="{{ $emp->getDocument('degree') }}">
                                <input type="hidden" class="doc_certificate" value="{{ $emp->getDocument('certificate') }}">
                                <input type="hidden" class="doc_hsc_marksheet" value="{{ $emp->getDocument('hsc_marksheet') }}">
                                <input type="hidden" class="doc_ssc_marksheet" value="{{ $emp->getDocument('ssc_marksheet') }}">
                                <input type="hidden" class="doc_cv" value="{{ $emp->getDocument('cv') }}">
                                <input type="hidden" class="casual_leave_dates"
                                    value="{{ $emp->leaves->pluck('start_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->implode(', ') }}">
                            </div>
                        @empty
                            <div class="empty-state" style="grid-column: 1/-1;">
                                <i class="fa fa-users"></i>
                                <p>No employees found. Add your first employee!</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="px-4 py-3 border-top">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Professional Employee Modal -->
    <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header gradient text-white d-flex justify-content-between align-items-center">
                    <div class="modal-title-wrapper">
                        <div class="modal-title-icon" id="modalIcon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <div>
                            <h5 class="modal-title" id="modalLabel">Add Employee</h5>
                            <p class="modal-subtitle" id="modalSubLabel">Configure organization credentials, job roles & details</p>
                        </div>
                    </div>
                    <button type="button" class="modal-close-btn" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" title="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <form id="employeeForm" action="{{ route('hr.employees.store') }}" method="POST"
                    enctype="multipart/form-data" data-ajax-validate="true">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="modal-body">

                        <!-- Section 1: Personal & Basic Information -->
                        <div class="form-section-banner">
                            <i class="fa fa-user-circle"></i>
                            <span>1. Personal & Contact Information</span>
                        </div>
                        <div class="form-card-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-user"></i> First Name <span class="required-star">*</span>
                                        </label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            placeholder="e.g. Kashan" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-user"></i> Last Name <span class="required-star">*</span>
                                        </label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                            placeholder="e.g. Khan" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-phone"></i> Contact Phone
                                        </label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            placeholder="e.g. 03001234567">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-toggle-on"></i> Employee Status <span class="required-star">*</span>
                                        </label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="active">Active (Currently Employed)</option>
                                            <option value="non-active">Non-Active (Suspended / On Hold)</option>
                                            <option value="terminated">Terminated (Discharged)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: System & Portal Login Access (Toggleable) -->
                        <div class="form-section-banner">
                            <i class="fa fa-shield-alt"></i>
                            <span>2. System & Portal Login Access</span>
                        </div>
                        <div class="form-card-box">
                            <div class="modern-switch-box" id="portal_access_switch_box">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="switch-icon-circle">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">Assign Portal Login Access</div>
                                        <small class="text-muted">Enable if this employee needs login credentials to access the ERP / web portal</small>
                                    </div>
                                </div>
                                <label class="modern-switch mb-0">
                                    <input type="checkbox" name="assign_portal_access" id="assign_portal_access" value="1">
                                    <span class="switch-slider"></span>
                                </label>
                            </div>

                            <!-- Credentials Row (Hidden by default, slides down when toggled ON) -->
                            <div id="portal_credentials_container" style="display: none; margin-top: 16px; padding-top: 14px; border-top: 1px dashed #e2e8f0;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label">
                                                <i class="fa fa-envelope"></i> Portal Email Address <span class="required-star">*</span>
                                            </label>
                                            <input type="email" name="email" id="email" class="form-control"
                                                placeholder="e.g. employee@company.com">
                                            <small class="text-muted">Login username for ERP portal</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label class="form-label">
                                                <i class="fa fa-lock"></i> Portal Password
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password" class="form-control"
                                                    placeholder="Default: 12345678 (blank to keep)">
                                                <button class="btn toggle-password" type="button" data-target="password" title="Toggle Visibility">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">Minimum 6 characters</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Department, Designation & Shift Assignment -->
                        <div class="form-section-banner">
                            <i class="fa fa-briefcase"></i>
                            <span>3. Department, Designation & Shift</span>
                        </div>
                        <div class="form-card-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-building"></i> Department <span class="required-star">*</span>
                                        </label>
                                        <select name="department_id" id="department_id" class="form-select" required>
                                            <option value="">-- Choose Department --</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-award"></i> Designation <span class="required-star">*</span>
                                        </label>
                                        <select name="designation_id" id="designation_id" class="form-select" required>
                                            <option value="">-- Choose Designation --</option>
                                            @foreach ($designations as $des)
                                                <option value="{{ $des->id }}">{{ $des->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-clock"></i> Assigned Shift
                                        </label>
                                        <select name="shift_id" id="shift_id" class="form-select">
                                            @php
                                                $defaultShift = $shifts->where('is_default', true)->first();
                                                $otherShifts = $shifts->where('is_default', false);
                                            @endphp
                                            @if ($defaultShift)
                                                <option value="{{ $defaultShift->id }}">
                                                    Default - {{ $defaultShift->name }}
                                                    ({{ \Carbon\Carbon::parse($defaultShift->start_time)->format('h:i A') }} -
                                                    {{ \Carbon\Carbon::parse($defaultShift->end_time)->format('h:i A') }})
                                                </option>
                                            @else
                                                <option value="">Default (9:00 AM - 6:00 PM)</option>
                                            @endif
                                            @foreach ($otherShifts as $shift)
                                                <option value="{{ $shift->id }}">{{ $shift->name }}
                                                    ({{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} -
                                                    {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }})
                                                </option>
                                            @endforeach
                                            <option value="custom">Custom Timing (Specify Hours)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-calendar-alt"></i> Joining Date <span class="required-star">*</span>
                                        </label>
                                        <input type="date" name="joining_date" id="joining_date" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Custom Time Container (Slides down when custom shift selected) -->
                                <div id="custom_time_container" class="col-12" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-modern mb-2">
                                                <label class="form-label text-warning font-weight-bold">
                                                    <i class="fa fa-hourglass-start text-warning"></i> Custom Start Time
                                                </label>
                                                <input type="time" name="custom_start_time" id="custom_start_time" class="form-control">
                                                <small class="text-muted">Start of working hours</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-modern mb-2">
                                                <label class="form-label text-warning font-weight-bold">
                                                    <i class="fa fa-hourglass-end text-warning"></i> Custom End Time
                                                </label>
                                                <input type="time" name="custom_end_time" id="custom_end_time" class="form-control">
                                                <small class="text-muted">End of working hours</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Casual Leaves & Address -->
                        <div class="form-section-banner">
                            <i class="fa fa-map-marked-alt"></i>
                            <span>4. Casual Leaves & Residential Address</span>
                        </div>
                        <div class="form-card-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label class="form-label" for="casual_leave_days">
                                            <i class="fa fa-calendar-check"></i> Casual Leave Dates
                                        </label>
                                        <input type="text" name="casual_leave_days" id="casual_leave_days" class="form-control"
                                            placeholder="Click to pick one or multiple dates...">
                                        <small class="text-muted d-block mt-1">
                                            <i class="fa fa-info-circle text-primary me-1"></i>
                                            Select approved future dates for scheduled casual leaves (auto-marked in attendance).
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group-modern">
                                        <label class="form-label">
                                            <i class="fa fa-map-marker-alt"></i> Residential Address
                                        </label>
                                        <textarea name="address" id="address" class="form-control" rows="2"
                                            placeholder="Enter complete residential or permanent address..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Documents -->
                        <div class="form-section-banner">
                            <i class="fa fa-folder-open"></i>
                            <span>5. Verification Documents</span>
                        </div>
                        <div class="form-card-box">
                            <div class="modern-switch-box" id="docs_switch_box">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="switch-icon-circle" style="background: #e0e7ff; color: #4338ca;">
                                        <i class="fa fa-file-invoice"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">Documents Submitted</div>
                                        <small class="text-muted">Toggle ON if employee has provided verification/educational documents</small>
                                    </div>
                                </div>
                                <label class="modern-switch mb-0">
                                    <input type="checkbox" name="is_docs_submitted" id="is_docs_submitted" value="1">
                                    <span class="switch-slider"></span>
                                </label>
                            </div>

                            <!-- Document Upload Tiles Container -->
                            <div id="documents_container" class="row" style="display: none; margin-top: 14px; padding-top: 14px; border-top: 1px dashed #e2e8f0;">
                                <div class="col-md-6">
                                    <div class="doc-upload-tile">
                                        <div class="doc-title-row">
                                            <span class="doc-name"><i class="fa fa-graduation-cap"></i> Degree Certificate</span>
                                            <span id="link_degree"></span>
                                        </div>
                                        <input type="file" name="document_degree" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="doc-upload-tile">
                                        <div class="doc-title-row">
                                            <span class="doc-name"><i class="fa fa-certificate"></i> Experience / Certificate</span>
                                            <span id="link_certificate"></span>
                                        </div>
                                        <input type="file" name="document_certificate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="doc-upload-tile">
                                        <div class="doc-title-row">
                                            <span class="doc-name"><i class="fa fa-file-alt"></i> Intermediate (HSC)</span>
                                            <span id="link_hsc_marksheet"></span>
                                        </div>
                                        <input type="file" name="document_hsc_marksheet" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="doc-upload-tile">
                                        <div class="doc-title-row">
                                            <span class="doc-name"><i class="fa fa-file-alt"></i> Matric (SSC)</span>
                                            <span id="link_ssc_marksheet"></span>
                                        </div>
                                        <input type="file" name="document_ssc_marksheet" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="doc-upload-tile">
                                        <div class="doc-title-row">
                                            <span class="doc-name"><i class="fa fa-id-badge"></i> Curriculum Vitae (CV)</span>
                                            <span id="link_cv"></span>
                                        </div>
                                        <input type="file" name="document_cv" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer-modern">
                        <button type="button" class="btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn-save" id="btnSaveEmployee">
                            <i class="fa fa-check"></i>
                            <span id="saveBtnText">Save Employee</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Face Registration Modal -->
    <div class="modal fade" id="faceModal" tabindex="-1" role="dialog" aria-labelledby="faceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header gradient text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0ea5e9, #2563eb); padding: 18px 24px;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-camera-retro font-size-lg"></i>
                        <h5 class="modal-title text-white mb-0" id="faceModalLabel">Register Face ID</h5>
                    </div>
                    <button type="button" class="modal-close-btn" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body text-center p-4">
                    <input type="hidden" id="face_employee_id">
                    <div style="position: relative; width: 100%; border-radius: 12px; overflow: hidden; background: #000; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                        <video id="face-video" autoplay playsinline style="width: 100%; display: block;"></video>
                        <canvas id="face-canvas" style="display: none;"></canvas>
                        <div style="position: absolute; top:50%; left:50%; transform: translate(-50%, -50%); width: 220px; height: 280px; border: 3px dashed rgba(255,255,255,0.7); border-radius: 50%; pointer-events: none;">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mb-3" style="min-height: 30px;">
                        <div id="status-indicator" class="status-dot"></div>
                    </div>
                    <div id="face_status" class="mb-3"></div>
                    <button type="button" class="btn btn-primary w-100 py-2 font-weight-bold" id="btn-capture-face" disabled style="border-radius: 9px; background: linear-gradient(135deg, #0ea5e9, #2563eb); border: none;">
                        <i class="fa fa-camera me-1"></i> Capture & Save Face ID
                    </button>
                    <small class="text-muted mt-2 d-block">Position your face inside the oval guide and look directly at the camera.</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .status-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #e9ecef;
            transition: all 0.3s ease;
        }
        .status-dot.yellow {
            background: #ffc107;
            box-shadow: 0 0 12px #ffc107;
            animation: pulse-dot 1.5s infinite;
        }
        .status-dot.green {
            background: #198754;
            box-shadow: 0 0 12px #198754;
            transform: scale(1.1);
        }
        .status-dot.red {
            background: #dc3545;
            box-shadow: 0 0 12px #dc3545;
            animation: shake-dot 0.4s;
        }
        @keyframes pulse-dot {
            0% { opacity: 0.5; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); }
            100% { opacity: 0.5; transform: scale(0.8); }
        }
        @keyframes shake-dot {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize Flatpickr for Casual Leave multiple dates
            let casualLeavePicker = null;
            if (typeof flatpickr !== 'undefined') {
                casualLeavePicker = flatpickr('#casual_leave_days', {
                    mode: 'multiple',
                    dateFormat: 'Y-m-d',
                    conjunction: ', ',
                    allowInput: true
                });
            }

            // Toggle custom shift fields
            $('#shift_id').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#custom_time_container').slideDown(250);
                } else {
                    $('#custom_time_container').slideUp(200);
                    $('#custom_start_time').val('');
                    $('#custom_end_time').val('');
                }
            });

            // Toggle Portal Access credentials
            $('#assign_portal_access').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#portal_credentials_container').slideDown(250);
                    $('#email').prop('required', true);
                } else {
                    $('#portal_credentials_container').slideUp(200);
                    $('#email').prop('required', false);
                }
            });

            // Toggle documents container
            $('#is_docs_submitted').on('change', function() {
                $(this).is(':checked') ? $('#documents_container').slideDown(250) : $('#documents_container').slideUp(200);
            });

            // Password Toggle Show/Hide
            $(document).on('click', '.toggle-password', function() {
                var targetId = $(this).data('target');
                var input = $('#' + targetId);
                var icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Create Employee Click Handler
            $(document).on('click', '#createBtn', function(e) {
                e.preventDefault();
                $('#edit_id').val('');
                $('#employeeForm')[0].reset();
                $('#is_docs_submitted').prop('checked', false);
                $('#documents_container').hide();
                $('#custom_time_container').hide();

                // Reset Portal Access Switch
                $('#assign_portal_access').prop('checked', false);
                $('#portal_credentials_container').hide();
                $('#email').val('').prop('required', false);
                $('#password').val('');

                $('#shift_id').val($('#shift_id option:first').val());
                $('#link_degree, #link_certificate, #link_hsc_marksheet, #link_ssc_marksheet, #link_cv').html('');

                $('#modalIcon').html('<i class="fa fa-user-plus"></i>');
                $('#modalLabel').text('Add Employee');
                $('#modalSubLabel').text('Configure organization credentials, job roles & details');
                $('#saveBtnText').text('Save Employee');

                if (casualLeavePicker) {
                    casualLeavePicker.clear();
                }
                $('#casual_leave_days').val('');

                $('#employeeModal').modal('show');
            });

            // Edit Employee Click Handler
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                var card = $(this).closest('.hr-item-card');
                $('#edit_id').val(card.data('id'));
                $('#first_name').val(card.find('.first_name').val());
                $('#last_name').val(card.find('.last_name').val());
                $('#phone').val(card.find('.phone').val());
                $('#address').val(card.find('.address').val());
                $('#department_id').val(card.find('.department_id').val());
                $('#designation_id').val(card.find('.designation_id').val());

                // Handle Portal Access State
                var hasPortal = card.find('.has_portal_access').val() == '1';
                var empEmail = card.find('.email').val();
                if (hasPortal || (empEmail && empEmail !== '')) {
                    $('#assign_portal_access').prop('checked', true);
                    $('#email').val(empEmail).prop('required', true);
                    $('#portal_credentials_container').show();
                } else {
                    $('#assign_portal_access').prop('checked', false);
                    $('#email').val('').prop('required', false);
                    $('#portal_credentials_container').hide();
                }
                $('#password').val('');

                // Handle Shift/Custom Logic
                var customStart = card.find('.custom_start_time').val();
                if (customStart && customStart !== '') {
                    $('#shift_id').val('custom');
                    $('#custom_start_time').val(card.find('.custom_start_time').val());
                    $('#custom_end_time').val(card.find('.custom_end_time').val());
                    $('#custom_time_container').show();
                } else {
                    $('#shift_id').val(card.find('.shift_id').val());
                    $('#custom_time_container').hide();
                }

                $('#joining_date').val(card.find('.joining_date').val());
                $('#status').val(card.find('.status').val());

                if (card.find('.is_docs_submitted').val() == '1') {
                    $('#is_docs_submitted').prop('checked', true);
                    $('#documents_container').show();
                } else {
                    $('#is_docs_submitted').prop('checked', false);
                    $('#documents_container').hide();
                }

                function setLink(id, filepath) {
                    if (filepath && filepath !== '') {
                        $('#' + id).html('<a href="{{ asset('') }}' + filepath + '" target="_blank" class="badge bg-primary text-white text-decoration-none py-1 px-2"><i class="fa fa-external-link-alt me-1"></i> View</a>');
                    } else {
                        $('#' + id).html('');
                    }
                }

                setLink('link_degree', card.find('.doc_degree').val());
                setLink('link_certificate', card.find('.doc_certificate').val());
                setLink('link_hsc_marksheet', card.find('.doc_hsc_marksheet').val());
                setLink('link_ssc_marksheet', card.find('.doc_ssc_marksheet').val());
                setLink('link_cv', card.find('.doc_cv').val());

                // Load Casual Leave Dates
                var leaveDates = card.find('.casual_leave_dates').val() || '';
                $('#casual_leave_days').val(leaveDates);
                if (casualLeavePicker) {
                    if (leaveDates) {
                        casualLeavePicker.setDate(leaveDates.split(',').map(function(s) { return s.trim(); }).filter(Boolean));
                    } else {
                        casualLeavePicker.clear();
                    }
                }

                $('#modalIcon').html('<i class="fa fa-user-edit"></i>');
                $('#modalLabel').text('Edit Employee: ' + card.find('.first_name').val() + ' ' + card.find('.last_name').val());
                $('#modalSubLabel').text('Update employee credentials, department assignment & docs');
                $('#saveBtnText').text('Update Changes');

                $('#employeeModal').modal('show');
            });

            // Delete Employee Click Handler
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Delete Employee?',
                    text: "This will remove the employee account, documents, and records!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Deleted!', response.success, 'success')
                                        .then(() => location.reload());
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Failed to delete employee.', 'error');
                            }
                        });
                    }
                });
            });

            // Unified Filtering Function
            function filterEmployees() {
                var searchText = ($('#empSearch').val() || '').toLowerCase();
                var selectedDept = ($('#deptFilter').val() || 'all').toLowerCase();
                var selectedDesig = ($('#desigFilter').val() || 'all').toLowerCase();
                var selectedStatus = ($('#statusFilter').val() || 'all').toLowerCase();
                var activeToggle = $('.emp-seg-btn.active').data('filter') || 'all';

                $('.hr-item-card').each(function() {
                    var card = $(this);
                    var name = (card.data('name') || '').toString().toLowerCase();
                    var email = (card.data('email') || '').toString().toLowerCase();
                    var dept = (card.data('dept') || '').toString().toLowerCase();
                    var desig = (card.data('desig') || '').toString().toLowerCase();
                    var status = (card.data('status') || '').toString().toLowerCase();
                    var isCustom = card.find('.custom_start_time').val() ? true : false;

                    var matchSearch = !searchText || name.includes(searchText) || email.includes(searchText) || dept.includes(searchText) || desig.includes(searchText);
                    var matchDept = (selectedDept === 'all' || dept === selectedDept || dept.includes(selectedDept));
                    var matchDesig = (selectedDesig === 'all' || desig === selectedDesig || desig.includes(selectedDesig));
                    var matchStatus = (selectedStatus === 'all' || status === selectedStatus);

                    var matchToggle = true;
                    if (activeToggle === 'custom_time') matchToggle = isCustom;
                    if (activeToggle === 'default_shift') matchToggle = !isCustom;

                    if (matchSearch && matchDept && matchDesig && matchStatus && matchToggle) {
                        card.show();
                    } else {
                        card.hide();
                    }
                });
                $('#empCount').text($('.hr-item-card:visible').length + ' employees');
            }

            $('#empSearch').on('input', filterEmployees);
            $('#deptFilter, #desigFilter, #statusFilter').on('change', filterEmployees);
            $('.emp-seg-btn').on('click', function() {
                $('.emp-seg-btn').removeClass('active');
                $(this).addClass('active');
                filterEmployees();
            });
            $('#refreshBtn').on('click', () => location.reload());

            // --- Face Registration Logic ---
            let faceStream = null;
            let isModelsLoaded = false;
            const MODEL_URL = 'https://justadudewhohacks.github.io/face-api.js/models';

            function setFaceStatus(state, message) {
                const indicator = $('#status-indicator');
                indicator.removeClass('yellow green red');

                if (state === 'loading' || state === 'detecting') {
                    indicator.addClass('yellow');
                } else if (state === 'ready' || state === 'success') {
                    indicator.addClass('green');
                } else if (state === 'error') {
                    indicator.addClass('red');
                }

                if (message) {
                    $('#face_status').html(message);
                }
            }

            async function ensureModelsLoaded() {
                if (isModelsLoaded) return true;
                if (typeof faceapi === 'undefined') {
                    setFaceStatus('error', '<div class="text-danger small">Face API library is not available.</div>');
                    return false;
                }
                try {
                    setFaceStatus('loading', '<div class="text-info small"><i class="fa fa-spinner fa-spin me-1"></i> Loading AI models...</div>');
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                        faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                        faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
                    ]);
                    isModelsLoaded = true;
                    setFaceStatus('ready', '<div class="text-success small"><i class="fa fa-check-circle me-1"></i> Models loaded successfully.</div>');
                    return true;
                } catch (err) {
                    console.error('Failed to load FaceAPI models', err);
                    setFaceStatus('error', '<div class="text-danger small">Failed to load face detection models.</div>');
                    return false;
                }
            }

            $(document).on('click', '.register-face-btn', async function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#face_employee_id').val(id);
                $('#faceModalLabel').text('Register Face: ' + name);
                $('#face_status').empty();
                $('#btn-capture-face').prop('disabled', true);
                $('#faceModal').modal('show');

                const loaded = await ensureModelsLoaded();
                if (loaded) {
                    startFaceCamera();
                }
            });

            async function startFaceCamera() {
                try {
                    setFaceStatus('loading', '<div class="text-info small"><i class="fa fa-spinner fa-spin me-1"></i> Accessing camera...</div>');

                    if (faceStream) {
                        faceStream.getTracks().forEach(track => track.stop());
                    }

                    faceStream = await navigator.mediaDevices.getUserMedia({
                        video: { width: 640, height: 480, facingMode: 'user' }
                    });

                    const videoEl = document.getElementById('face-video');
                    if (videoEl) {
                        videoEl.srcObject = faceStream;
                        setFaceStatus('ready', '<div class="text-primary small"><i class="fa fa-info-circle me-1"></i> Ready! Look into the camera circle and click Capture.</div>');
                        $('#btn-capture-face').prop('disabled', false);
                    }
                } catch (err) {
                    let msg = err.message;
                    if (msg.includes('Permission denied')) msg = "Camera permission was denied.";
                    setFaceStatus('error', '<div class="text-danger small"><i class="fa fa-exclamation-circle me-1"></i> ' + msg + '</div>');
                    console.error(err);
                }
            }

            $('#btn-capture-face').on('click', async function() {
                const btn = $(this);
                const videoEl = document.getElementById('face-video');
                const canvasEl = document.getElementById('face-canvas');

                if (!videoEl || !canvasEl || typeof faceapi === 'undefined') return;

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Processing...');
                setFaceStatus('detecting', '<div class="text-warning small"><i class="fa fa-search me-1"></i> Detecting face...</div>');

                try {
                    const detections = await faceapi.detectSingleFace(videoEl, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (!detections) {
                        setFaceStatus('error', '<div class="text-danger small"><i class="fa fa-times-circle me-1"></i> No face detected! Please position your face clearly.</div>');
                        btn.prop('disabled', false).html('<i class="fa fa-camera me-1"></i> Capture & Save Face ID');
                        return;
                    }

                    const context = canvasEl.getContext('2d');
                    canvasEl.width = videoEl.videoWidth;
                    canvasEl.height = videoEl.videoHeight;
                    context.drawImage(videoEl, 0, 0);
                    const image = canvasEl.toDataURL('image/jpeg');

                    $.ajax({
                        url: '{{ route('hr.employees.face-register') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            employee_id: $('#face_employee_id').val(),
                            descriptor: Array.from(detections.descriptor),
                            image: image
                        },
                        success: function(res) {
                            setFaceStatus('success', '<div class="text-success small"><i class="fa fa-check-circle me-1"></i> ' + (res.success || 'Face ID Saved!') + '</div>');
                            btn.html('<i class="fa fa-check me-1"></i> Saved');
                            setTimeout(() => {
                                $('#faceModal').modal('hide');
                                location.reload();
                            }, 1000);
                        },
                        error: function(err) {
                            let msg = err.responseJSON && err.responseJSON.errors ? Object.values(err.responseJSON.errors)[0][0] : 'Error saving face.';
                            setFaceStatus('error', '<div class="text-danger small"><i class="fa fa-exclamation-circle me-1"></i> ' + msg + '</div>');
                            btn.prop('disabled', false).html('<i class="fa fa-camera me-1"></i> Capture & Save Face ID');
                        }
                    });
                } catch (err) {
                    console.error(err);
                    setFaceStatus('error', '<div class="text-danger small"><i class="fa fa-exclamation-circle me-1"></i> ' + err.message + '</div>');
                    btn.prop('disabled', false).html('<i class="fa fa-camera me-1"></i> Capture & Save Face ID');
                }
            });

            // Stop camera on face modal close
            $('#faceModal').on('hidden.bs.modal', function() {
                if (faceStream) {
                    faceStream.getTracks().forEach(track => track.stop());
                    faceStream = null;
                }
                setFaceStatus('reset');
                $('#face_status').empty();
                $('#btn-capture-face').prop('disabled', true).html('<i class="fa fa-camera me-1"></i> Capture & Save Face ID');
            });

            // Employee Monthly Detail Modal Logic
            let currentDetailEmpId = null;

            $(document).on('click', '.view-monthly-detail-btn', function() {
                let empId = $(this).data('id');
                currentDetailEmpId = empId;
                
                let now = new Date();
                let monthStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
                $('#empDetailMonthPicker').val(monthStr);

                loadEmployeeMonthlyDetail(empId, monthStr);
                $('#monthlyDetailModal').modal('show');
            });

            $('#empDetailFetchBtn').on('click', function() {
                let monthStr = $('#empDetailMonthPicker').val();
                if (currentDetailEmpId && monthStr) {
                    loadEmployeeMonthlyDetail(currentDetailEmpId, monthStr);
                }
            });

            $('#empDetailCurrentMonthBtn').on('click', function() {
                let now = new Date();
                let monthStr = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
                $('#empDetailMonthPicker').val(monthStr);
                if (currentDetailEmpId) {
                    loadEmployeeMonthlyDetail(currentDetailEmpId, monthStr);
                }
            });

            $('#empDetailPrevMonthBtn').on('click', function() {
                let currentVal = $('#empDetailMonthPicker').val();
                if (!currentVal) return;
                let parts = currentVal.split('-');
                let year = parseInt(parts[0]);
                let month = parseInt(parts[1]) - 1;
                if (month === 0) {
                    month = 12;
                    year--;
                }
                let monthStr = year + '-' + String(month).padStart(2, '0');
                $('#empDetailMonthPicker').val(monthStr);
                if (currentDetailEmpId) {
                    loadEmployeeMonthlyDetail(currentDetailEmpId, monthStr);
                }
            });

            function loadEmployeeMonthlyDetail(empId, monthStr) {
                $('#empDetailLoader').show();
                $('#empDetailContent').hide();

                $.ajax({
                    url: '/hr/employees/' + empId + '/monthly-detail',
                    type: 'GET',
                    data: { month: monthStr },
                    success: function(res) {
                        $('#empDetailLoader').hide();
                        $('#empDetailContent').show();

                        if (res.success) {
                            let emp = res.employee;
                            let sum = res.summary;

                            $('#empDetailName').text(emp.name + ' - Monthly Detail (' + sum.month_name + ')');
                            $('#empDetailMeta').html('<i class="fa fa-building me-1"></i>' + emp.department + ' &nbsp;•&nbsp; <i class="fa fa-briefcase me-1"></i>' + emp.designation + ' &nbsp;•&nbsp; <i class="fa fa-clock me-1"></i>Shift: ' + emp.shift + ' &nbsp;•&nbsp; ID #' + emp.id);

                            $('#kpiTotalDays').text(sum.total_days);
                            $('#kpiPresent').text(sum.present);
                            $('#kpiLate').text(sum.late);
                            $('#kpiLateMins').text(sum.total_late_minutes + ' mins');
                            $('#kpiAbsent').text(sum.absent);
                            $('#kpiLeave').text(sum.leave);
                            $('#kpiHours').text(sum.total_hours + ' hrs');
                            $('#empDetailMonthName').text(sum.month_name);

                            // Payroll Box
                            if (res.payroll) {
                                $('#payrollStatusBadge').text(res.payroll.status).removeClass('bg-success bg-warning bg-danger').addClass(res.payroll.status.toLowerCase() === 'paid' ? 'bg-success' : 'bg-warning');
                                $('#payrollGross').text('Rs. ' + res.payroll.gross_salary);
                                $('#payrollAllowances').text('+ Rs. ' + res.payroll.total_allowances);
                                $('#payrollDeductions').text('- Rs. ' + res.payroll.total_deductions);
                                $('#payrollNet').text('Rs. ' + res.payroll.net_salary);
                                $('#empPayrollBox').show();
                            } else {
                                $('#empPayrollBox').hide();
                            }

                            // Daily records table
                            let rowsHtml = '';
                            res.daily_records.forEach(function(r) {
                                let badgeClass = 'bg-' + r.status_badge;
                                rowsHtml += `
                                    <tr>
                                        <td class="fw-bold">${r.date}</td>
                                        <td>${r.day}</td>
                                        <td><span class="badge ${badgeClass}">${r.status}</span></td>
                                        <td>${r.clock_in}</td>
                                        <td>${r.clock_out}</td>
                                        <td class="fw-semibold">${r.hours > 0 ? r.hours + ' hrs' : '-'}</td>
                                        <td class="${r.late_mins > 0 ? 'text-warning font-weight-bold' : ''}">${r.late_mins > 0 ? r.late_mins + 'm' : '-'}</td>
                                        <td class="small text-muted">${r.notes || '-'}</td>
                                    </tr>
                                `;
                            });
                            $('#empDetailTableBody').html(rowsHtml);
                        }
                    },
                    error: function(err) {
                        $('#empDetailLoader').hide();
                        alert('Failed to load employee monthly details.');
                    }
                });
            }
        });
    </script>

    <!-- Single Employee Monthly Detail Modal -->
    <div class="modal fade" id="monthlyDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 20px 28px;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 46px; height: 46px; border-radius: 12px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #38bdf8;">
                            <i class="fa fa-calendar-check"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold text-white mb-0" id="empDetailName">Employee Monthly Report</h5>
                            <p class="small text-white-50 mb-0" id="empDetailMeta">Department • Designation</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background: #f8fafc; max-height: 80vh; overflow-y: auto;">
                    
                    <!-- Month Selection & Filter Bar -->
                    <div class="bg-white p-3 rounded-3 shadow-sm border mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-bold text-secondary small mb-0"><i class="fa fa-filter text-primary me-1"></i> SELECT MONTH:</label>
                            <input type="month" id="empDetailMonthPicker" class="form-control form-control-sm font-weight-bold" style="width: 170px; border-radius: 8px;">
                            <button type="button" class="btn btn-sm btn-primary px-3" id="empDetailFetchBtn" style="border-radius: 8px;">
                                <i class="fa fa-search me-1"></i> Load Month
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3" id="empDetailCurrentMonthBtn" style="border-radius: 8px;">
                                <i class="fa fa-calendar-day me-1"></i> Current Month
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3" id="empDetailPrevMonthBtn" style="border-radius: 8px;">
                                <i class="fa fa-arrow-left me-1"></i> Previous Month
                            </button>
                        </div>
                    </div>

                    <!-- Loader -->
                    <div id="empDetailLoader" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                        <p class="text-muted mt-2 fw-semibold">Fetching monthly attendance & payroll records...</p>
                    </div>

                    <!-- Content Container (Hidden initially) -->
                    <div id="empDetailContent" style="display: none;">
                        
                        <!-- KPI Cards Row -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-2">
                                <div class="bg-white p-3 rounded-3 border shadow-sm text-center">
                                    <div class="text-muted small fw-bold">TOTAL DAYS</div>
                                    <div class="h4 font-weight-bold text-dark mt-1 mb-0" id="kpiTotalDays">0</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="bg-white p-3 rounded-3 border border-success shadow-sm text-center">
                                    <div class="text-success small fw-bold"><i class="fa fa-check-circle me-1"></i> PRESENT</div>
                                    <div class="h4 font-weight-bold text-success mt-1 mb-0" id="kpiPresent">0</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="bg-white p-3 rounded-3 border border-warning shadow-sm text-center">
                                    <div class="text-warning small fw-bold"><i class="fa fa-exclamation-triangle me-1"></i> LATE</div>
                                    <div class="h4 font-weight-bold text-warning mt-1 mb-0" id="kpiLate">0</div>
                                    <div class="small text-muted" id="kpiLateMins">0 mins</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="bg-white p-3 rounded-3 border border-danger shadow-sm text-center">
                                    <div class="text-danger small fw-bold"><i class="fa fa-times-circle me-1"></i> ABSENT</div>
                                    <div class="h4 font-weight-bold text-danger mt-1 mb-0" id="kpiAbsent">0</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="bg-white p-3 rounded-3 border border-info shadow-sm text-center">
                                    <div class="text-info small fw-bold"><i class="fa fa-umbrella-beach me-1"></i> LEAVE</div>
                                    <div class="h4 font-weight-bold text-info mt-1 mb-0" id="kpiLeave">0</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="bg-white p-3 rounded-3 border shadow-sm text-center" style="border-color: #7c3aed !important;">
                                    <div class="small fw-bold" style="color:#7c3aed"><i class="fa fa-clock me-1"></i> WORKING HRS</div>
                                    <div class="h4 font-weight-bold mt-1 mb-0" style="color:#7c3aed" id="kpiHours">0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payroll Summary Box -->
                        <div id="empPayrollBox" class="bg-white p-3 rounded-3 border shadow-sm mb-4" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                                <span class="fw-bold text-dark"><i class="fa fa-file-invoice-dollar text-primary me-2"></i> Payroll Status for Month</span>
                                <span class="badge bg-success" id="payrollStatusBadge">Paid</span>
                            </div>
                            <div class="row text-center g-2">
                                <div class="col-md-3">
                                    <span class="text-muted small">Gross Salary:</span>
                                    <div class="fw-bold text-dark" id="payrollGross">Rs. 0</div>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted small">Allowances:</span>
                                    <div class="fw-bold text-success" id="payrollAllowances">+ Rs. 0</div>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted small">Deductions:</span>
                                    <div class="fw-bold text-danger" id="payrollDeductions">- Rs. 0</div>
                                </div>
                                <div class="col-md-3">
                                    <span class="text-muted small">Net Payable:</span>
                                    <div class="h5 fw-bold text-primary mb-0" id="payrollNet">Rs. 0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Daily Attendance Table -->
                        <div class="bg-white rounded-3 border shadow-sm overflow-hidden">
                            <div class="px-3 py-2 bg-light border-bottom fw-bold text-dark d-flex align-items-center justify-content-between">
                                <span><i class="fa fa-list me-2 text-primary"></i> Daily Attendance Log (<span id="empDetailMonthName">Month</span>)</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.88rem;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Date</th>
                                            <th>Day</th>
                                            <th>Status</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Hours</th>
                                            <th>Late Mins</th>
                                            <th>Notes / Location</th>
                                        </tr>
                                    </thead>
                                    <tbody id="empDetailTableBody">
                                        <!-- Dynamic Rows -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-secondary px-4 font-weight-bold" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius: 8px;">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush
