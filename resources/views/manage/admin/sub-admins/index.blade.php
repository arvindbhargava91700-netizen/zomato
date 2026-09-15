@extends('layouts.admin.main')

@section('title', 'Sub Admins Management - Admin Dashboard')

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
    .role-chip {
        background: rgb(203 32 45 / 0.08);
        color: #cb202d;
        border: 1px solid rgb(203 32 45 / 0.2);
        border-radius: 30px;
        padding: 3px 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }
</style>
@endpush

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Sub Admin Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Roles & Permissions</li>
                <li class="breadcrumb-item">Sub Admins</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.sub-admins.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add Sub Admin
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.sub-admins.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email or mobile..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Search</button>
                        <a href="{{ route('admin.sub-admins.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Sub Admins List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Email / Mobile</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Last Login</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subAdmins as $subAdmin)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-circle">{{ strtoupper(substr($subAdmin->name, 0, 1)) }}</div>
                                            <div class="fw-bold text-dark fs-6">{{ $subAdmin->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark fs-12">{{ $subAdmin->email }}</div>
                                        @if($subAdmin->mobile)
                                            <div class="text-muted fs-12">{{ $subAdmin->mobile }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subAdmin->assignedRole)
                                            <span class="role-chip">{{ $subAdmin->assignedRole->name }}</span>
                                        @elseif($subAdmin->role_slug)
                                            <span class="role-chip">{{ str_replace('-', ' ', $subAdmin->role_slug) }}</span>
                                        @else
                                            <span class="role-chip">{{ str_replace('_', ' ', $subAdmin->role) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subAdmin->status === 'active')
                                            <span class="badge bg-soft-success text-success fw-semibold fs-12">Active</span>
                                        @else
                                            <span class="badge bg-soft-secondary text-secondary fw-semibold fs-12">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">{{ $subAdmin->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td>
                                        @if($subAdmin->last_login)
                                            <span class="text-muted fs-12">{{ $subAdmin->last_login->format('d M Y') }}</span>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <a href="{{ route('admin.sub-admins.edit', $subAdmin->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" title="Edit" data-bs-toggle="tooltip">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.sub-admins.toggle-status', $subAdmin->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-light border text-warning p-2 rounded-2" title="{{ $subAdmin->status === 'active' ? 'Deactivate' : 'Activate' }}" data-bs-toggle="tooltip">
                                                    @if($subAdmin->status === 'active')
                                                        <i class="feather-toggle-left"></i>
                                                    @else
                                                        <i class="feather-toggle-right"></i>
                                                    @endif
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.sub-admins.destroy', $subAdmin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sub admin?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" title="Delete" data-bs-toggle="tooltip">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="feather-user-x display-6 d-block mb-2 text-secondary"></i>
                                        No sub admins found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($subAdmins->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $subAdmins->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection