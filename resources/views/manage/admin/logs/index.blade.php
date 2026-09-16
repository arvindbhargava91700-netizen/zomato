@extends('layouts.admin.main')

@section('title', getPageTitle('Admin Logs'))

@push('styles')
<style>
    .avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #cb202d, #a51523);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    .action-chip {
        border-radius: 30px;
        padding: 3px 12px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .action-chip.LOGIN {
        background: rgb(203 32 45 / 0.1);
        color: #cb202d;
        border: 1px solid rgb(203 32 45 / 0.25);
    }
    .action-chip.LOGOUT {
        background: rgb(59 130 246 / 0.1);
        color: #2563eb;
        border: 1px solid rgb(59 130 246 / 0.25);
    }
    .device-chip {
        background: #f1f5f9;
        border-radius: 30px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        color: #334155;
    }
</style>
@endpush

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Admin Logs</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Roles & Permissions</li>
                <li class="breadcrumb-item">Admin Logs</li>
            </ul>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.logs.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label text-muted fs-12 mb-1">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Name, email, IP, description..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted fs-12 mb-1">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted fs-12 mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted fs-12 mb-1">From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-3">Search</button>
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Admin Activity Logs</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Date & Time</th>
                                <th>Admin</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th class="pe-4">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark fs-12">{{ $log->logged_at?->format('d M Y') }}</div>
                                        <div class="text-muted fs-12">{{ $log->logged_at?->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        @if($log->name)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle">{{ strtoupper(substr($log->name, 0, 1)) }}</div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-6">{{ $log->name }}</div>
                                                    <div class="text-muted fs-12">{{ $log->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-circle" style="background: linear-gradient(135deg, #94a3b8, #64748b);">?</div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-6">Unknown</div>
                                                    <div class="text-muted fs-12">{{ $log->email }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="action-chip {{ $log->action }}">{{ $log->action }}</span>
                                    </td>
                                    <td>
                                        @if($log->status === 'success')
                                            <span class="badge bg-soft-success text-success fw-semibold fs-12">Success</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger fw-semibold fs-12">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="fs-12 text-dark bg-light px-2 py-1 rounded-2">{{ $log->ip_address ?? '—' }}</code>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1" style="max-width: 150px;">
                                            <span class="device-chip"><i class="feather-{{ $log->device === 'Mobile' ? 'smartphone' : ($log->device === 'Tablet' ? 'tablet' : 'monitor') }} me-1"></i>{{ $log->device }}</span>
                                            <span class="device-chip">{{ $log->browser }}{{ $log->os ? ' · ' . $log->os : '' }}</span>
                                        </div>
                                    </td>
                                    <td class="pe-4">
                                        <span class="text-muted fs-12">{{ Str::limit($log->description, 60) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="feather-clipboard display-6 d-block mb-2 text-secondary"></i>
                                        No logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($logs->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection