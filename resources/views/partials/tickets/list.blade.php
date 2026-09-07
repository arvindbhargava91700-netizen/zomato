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

<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">My Tickets</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route($rp . 'index') }}">Support</a></li>
                <li class="breadcrumb-item">My Tickets</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route($rp . 'create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Raise a Ticket
            </a>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ticket ID</th>
                                <th>Subject</th>
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
                                    <td>{{ \Illuminate\Support\Str::limit($ticket->subject, 40) }}</td>
                                    <td>{{ \App\Models\Ticket::CATEGORIES[$ticket->category] ?? $ticket->category }}</td>
                                    <td><span class="badge {{ $priorityBadge[$ticket->priority] }}">{{ ucfirst($ticket->priority) }}</span></td>
                                    <td><span class="badge {{ $statusBadge[$ticket->status] }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span></td>
                                    <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                    <td><a href="{{ route($rp . 'show', $ticket->ticket_id) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">You have not raised any tickets yet.</td></tr>
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
