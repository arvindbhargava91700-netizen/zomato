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
        <div class="row g-2 mb-3">
            <div class="col"><div class="card stretch stretch-full border-0 shadow-sm rounded-3"><div class="card-body py-2 text-center"><div class="fw-bold">{{ $counts['open'] }}</div><div class="small text-muted">Open</div></div></div></div>
            <div class="col"><div class="card stretch stretch-full border-0 shadow-sm rounded-3"><div class="card-body py-2 text-center"><div class="fw-bold">{{ $counts['in_progress'] }}</div><div class="small text-muted">In Progress</div></div></div></div>
            <div class="col"><div class="card stretch stretch-full border-0 shadow-sm rounded-3"><div class="card-body py-2 text-center"><div class="fw-bold">{{ $counts['resolved'] }}</div><div class="small text-muted">Resolved</div></div></div></div>
            <div class="col"><div class="card stretch stretch-full border-0 shadow-sm rounded-3"><div class="card-body py-2 text-center"><div class="fw-bold">{{ $counts['closed'] }}</div><div class="small text-muted">Closed</div></div></div></div>
            <div class="col"><div class="card stretch stretch-full border-0 shadow-sm rounded-3"><div class="card-body py-2 text-center"><div class="fw-bold">{{ $counts['reopened'] }}</div><div class="small text-muted">Reopened</div></div></div></div>
        </div>

        <!-- Filters -->
        <form method="GET" class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach(['open','in_progress','resolved','closed','reopened'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-1">User Type</label>
                    <select name="user_type" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="customer" {{ request('user_type') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="restaurant" {{ request('user_type') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-1">Category</label>
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach(\App\Models\Ticket::CATEGORIES as $k => $l)
                            <option value="{{ $k }}" {{ request('category') == $k ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label small mb-1">Priority</label>
                    <select name="priority" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach(['low','medium','high','urgent'] as $p)
                            <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-sm btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">Filter</button>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket</th>
                                <th>Subject</th>
                                <th>User</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td class="fw-semibold">{{ $ticket->ticket_id }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($ticket->subject, 35) }}</td>
                                    <td>{{ $ticket->user?->name ?? ucfirst($ticket->user_type) }}<br><small class="text-muted">{{ ucfirst($ticket->user_type) }} #{{ $ticket->user_id }}</small></td>
                                    <td>{{ \App\Models\Ticket::CATEGORIES[$ticket->category] ?? $ticket->category }}</td>
                                    <td><span class="badge {{ $priorityBadge[$ticket->priority] }}">{{ ucfirst($ticket->priority) }}</span></td>
                                    <td><span class="badge {{ $statusBadge[$ticket->status] }}">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span></td>
                                    <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                    <td><a href="{{ route('admin.tickets.show', $ticket->ticket_id) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted py-4">No tickets found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($tickets->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $tickets->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
