@extends('layouts.front.main')

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">My Tickets</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i> Home</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('help') }}">Support</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Tickets</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <section class="section-b-space">
        <div class="container">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('tickets-create') }}" class="btn theme-btn"><i class="ri-add-line me-1"></i> Raise a Ticket</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm" style="border-radius:16px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Subject</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td class="fw-semibold">{{ $ticket->ticket_id }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($ticket->subject, 40) }}</td>
                                        <td>{{ \App\Models\Ticket::CATEGORIES[$ticket->category] ?? ucfirst($ticket->category) }}</td>
                                        <td>
                                            @php
                                                $pMap = [
                                                    'low' => 'bg-soft-secondary text-secondary',
                                                    'medium' => 'bg-soft-info text-info',
                                                    'high' => 'bg-soft-warning text-warning',
                                                    'urgent' => 'bg-soft-danger text-danger',
                                                ];
                                                $pClass = $pMap[$ticket->priority] ?? 'bg-soft-info text-info';
                                            @endphp
                                            <span class="badge {{ $pClass }} text-capitalize">{{ $ticket->priority }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $sMap = [
                                                    'open' => 'bg-soft-primary text-primary',
                                                    'in_progress' => 'bg-soft-info text-info',
                                                    'pending' => 'bg-soft-warning text-warning',
                                                    'resolved' => 'bg-soft-success text-success',
                                                    'closed' => 'bg-soft-secondary text-secondary',
                                                    'rejected' => 'bg-soft-danger text-danger',
                                                ];
                                                $sClass = $sMap[$ticket->status] ?? 'bg-soft-primary text-primary';
                                            @endphp
                                            <span class="badge {{ $sClass }} text-capitalize">{{ str_replace('_', ' ', $ticket->status) }}</span>
                                        </td>
                                        <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('tickets-show', $ticket->ticket_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="ri-inbox-line fs-1 d-block mb-2 text-light"></i>
                                            You have not raised any tickets yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($tickets->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-end">
                            {{ $tickets->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .table > :not(caption) > * > * { padding: .9rem 1rem; }
            .table thead th { font-size: .8rem; text-transform: uppercase; letter-spacing: .03em; color: #6c757d; }
        </style>
    @endpush
@endsection
