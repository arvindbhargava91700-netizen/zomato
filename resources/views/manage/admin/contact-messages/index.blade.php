@php
    $statusBadge = [
        'unread' => 'bg-soft-danger text-danger',
        'read' => 'bg-soft-primary text-primary',
        'replied' => 'bg-soft-success text-success',
        'closed' => 'bg-soft-secondary text-secondary',
    ];
@endphp

@extends('layouts.admin.main')

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Contact Inquiries</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Contact Inquiries</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3 border-0 shadow-sm" role="alert">
                <i class="feather-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Status Counters -->
        <div class="row g-2 mb-3">
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body py-2 text-center">
                        <div class="fw-bold fs-5">{{ $counts['all'] }}</div>
                        <div class="small text-muted">All Messages</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body py-2 text-center">
                        <div class="fw-bold fs-5 text-danger">{{ $counts['unread'] }}</div>
                        <div class="small text-muted">Unread</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body py-2 text-center">
                        <div class="fw-bold fs-5 text-primary">{{ $counts['read'] }}</div>
                        <div class="small text-muted">Read</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body py-2 text-center">
                        <div class="fw-bold fs-5 text-success">{{ $counts['replied'] }}</div>
                        <div class="small text-muted">Replied</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body py-2 text-center">
                        <div class="fw-bold fs-5 text-secondary">{{ $counts['closed'] }}</div>
                        <div class="small text-muted">Closed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        @foreach(['unread', 'read', 'replied', 'closed'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name, email, phone, subject..." value="{{ request('search') }}">
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="feather-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-sm btn-light border">Reset</a>
                </div>
            </div>
        </form>

        <!-- Messages Table -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3" style="width: 50px;">#</th>
                                <th>Sender</th>
                                <th>Contact Details</th>
                                <th>Subject</th>
                                <th>Message Snippet</th>
                                <th>Status</th>
                                <th>Received</th>
                                <th class="text-end pe-3" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                                <tr class="{{ $msg->status === 'unread' ? 'fw-bold bg-soft-light' : '' }}">
                                    <td class="ps-3">{{ $msg->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-text avatar-sm bg-soft-primary text-primary rounded-circle">
                                                {{ strtoupper(substr($msg->first_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div>{{ $msg->full_name }}</div>
                                                @if($msg->user_id)
                                                    <span class="badge bg-soft-success text-success fs-10">Registered User</span>
                                                @else
                                                    <span class="badge bg-soft-secondary text-secondary fs-10">Guest</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-12"><i class="feather-mail me-1 text-muted"></i>{{ $msg->email }}</div>
                                        @if($msg->phone)
                                            <div class="fs-12 text-muted"><i class="feather-phone me-1 text-muted"></i>{{ $msg->phone }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $msg->subject ?: 'General Inquiry' }}</span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $msg->message }}">
                                            {{ $msg->message }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge[$msg->status] ?? 'bg-light text-dark' }}">
                                            {{ ucfirst($msg->status) }}
                                        </span>
                                    </td>
                                    <td class="fs-12 text-muted">
                                        {{ $msg->created_at->diffForHumans() }}
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="btn btn-xs btn-light border" title="View & Reply">
                                                <i class="feather-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.contact-messages.destroy', $msg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-xs btn-light border text-danger" title="Delete">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="feather-inbox fs-2 mb-2 d-block"></i>
                                        No contact inquiries found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($messages->hasPages())
                <div class="card-footer bg-white border-top py-2">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
