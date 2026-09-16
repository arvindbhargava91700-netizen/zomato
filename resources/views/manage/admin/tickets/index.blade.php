@php
    $statusBadge = [
        'open' => 'bg-soft-primary text-primary',
        'in_progress' => 'bg-soft-warning text-warning',
        'resolved' => 'bg-soft-success text-success',
        'closed' => 'bg-soft-secondary text-secondary',
        'reopened' => 'bg-soft-danger text-danger',
    ];
    $priorityBadge = [
        'low' => 'bg-soft-secondary text-secondary',
        'medium' => 'bg-soft-info text-info',
        'high' => 'bg-soft-warning text-warning',
        'urgent' => 'bg-soft-danger text-danger',
    ];
@endphp

@extends('layouts.admin.main')

@section('title', getPageTitle('Support Tickets'))

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Support Tickets</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Support Tickets</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <!-- Status counters -->
        <div class="row g-3 mb-4">
            <!-- Open -->
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 bg-soft-primary overflow-hidden h-100">
                    <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center position-relative">
                        <i class="feather-inbox position-absolute opacity-25" style="font-size: 3rem; right: -10px; bottom: -10px; color: var(--bs-primary);"></i>
                        <h3 class="fw-bolder mb-1 text-primary">{{ $counts['open'] }}</h3>
                        <div class="text-primary fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Open</div>
                    </div>
                </div>
            </div>
            <!-- In Progress -->
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 bg-soft-warning overflow-hidden h-100">
                    <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center position-relative">
                        <i class="feather-clock position-absolute opacity-25" style="font-size: 3rem; right: -10px; bottom: -10px; color: var(--bs-warning);"></i>
                        <h3 class="fw-bolder mb-1 text-warning">{{ $counts['in_progress'] }}</h3>
                        <div class="text-warning fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">In Progress</div>
                    </div>
                </div>
            </div>
            <!-- Resolved -->
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 bg-soft-success overflow-hidden h-100">
                    <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center position-relative">
                        <i class="feather-check-circle position-absolute opacity-25" style="font-size: 3rem; right: -10px; bottom: -10px; color: var(--bs-success);"></i>
                        <h3 class="fw-bolder mb-1 text-success">{{ $counts['resolved'] }}</h3>
                        <div class="text-success fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Resolved</div>
                    </div>
                </div>
            </div>
            <!-- Closed -->
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 bg-soft-secondary overflow-hidden h-100">
                    <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center position-relative">
                        <i class="feather-archive position-absolute opacity-25" style="font-size: 3rem; right: -10px; bottom: -10px; color: var(--bs-secondary);"></i>
                        <h3 class="fw-bolder mb-1 text-secondary">{{ $counts['closed'] }}</h3>
                        <div class="text-secondary fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Closed</div>
                    </div>
                </div>
            </div>
            <!-- Reopened -->
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 bg-soft-danger overflow-hidden h-100">
                    <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center position-relative">
                        <i class="feather-rotate-ccw position-absolute opacity-25" style="font-size: 3rem; right: -10px; bottom: -10px; color: var(--bs-danger);"></i>
                        <h3 class="fw-bolder mb-1 text-danger">{{ $counts['reopened'] }}</h3>
                        <div class="text-danger fw-semibold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Reopened</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card stretch stretch-full mb-3 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small mb-1 fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            @foreach(['open','in_progress','resolved','closed','reopened'] as $s)
                                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1 fw-semibold">User Type</label>
                        <select name="user_type" class="form-select">
                            <option value="">All Users</option>
                            <option value="customer" {{ request('user_type') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="restaurant" {{ request('user_type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-1 fw-semibold">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\Ticket::CATEGORIES as $k => $l)
                                <option value="{{ $k }}" {{ request('category') == $k ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1 fw-semibold">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="">All Priorities</option>
                            @foreach(['low','medium','high','urgent'] as $p)
                                <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button class="btn btn-primary fw-semibold w-50">Filter</button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-light border text-secondary fw-semibold w-50">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket ID</th>
                                <th>Subject</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $ticket->ticket_id }}</td>
                                    <td>
                                        <div class="text-dark fw-semibold mb-1">{{ \Illuminate\Support\Str::limit($ticket->subject, 35) }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $ticket->user?->name ?? ucfirst($ticket->user_type) }}</div>
                                        <div class="small text-muted">{{ ucfirst($ticket->user_type) }} ID: {{ $ticket->user_id }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ \App\Models\Ticket::CATEGORIES[$ticket->category] ?? $ticket->category }}</span></td>
                                    <td><span class="badge {{ $priorityBadge[$ticket->priority] }} px-2 py-1">{{ ucfirst($ticket->priority) }}</span></td>
                                    <td><span class="badge {{ $statusBadge[$ticket->status] }} px-2 py-1">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span></td>
                                    <td>
                                        <div class="fw-semibold">{{ $ticket->created_at->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $ticket->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.tickets.show', $ticket->ticket_id) }}" class="btn btn-sm btn-light border text-primary fw-semibold">
                                            <i class="feather-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted py-5"><i class="feather-inbox fs-2 mb-2 d-block"></i> No tickets found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($tickets->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-center">
                        {{ $tickets->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
