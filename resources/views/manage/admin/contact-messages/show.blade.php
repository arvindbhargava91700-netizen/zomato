@php
    $statusBadge = [
        'unread' => 'bg-soft-danger text-danger border border-danger-subtle',
        'read' => 'bg-soft-primary text-primary border border-primary-subtle',
        'replied' => 'bg-soft-success text-success border border-success-subtle',
        'closed' => 'bg-soft-secondary text-secondary border border-secondary-subtle',
    ];
@endphp

@extends('layouts.admin.main')

@section('title', getPageTitle('Message Details'))

@section('content')
<div class="nxl-content">
    <!-- Page Header (Compact) -->
    <div class="page-header d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
        <div class="page-header-left">
            <div class="d-flex align-items-center gap-2">
                <h5 class="mb-0 fw-bold text-dark">Inquiry #{{ $contactMessage->id }}</h5>
                <span class="badge {{ $statusBadge[$contactMessage->status] ?? 'bg-light text-dark' }} px-2 py-1 fs-11 rounded-pill">
                    {{ ucfirst($contactMessage->status) }}
                </span>
                <span class="badge bg-light text-dark border px-2 py-1 fs-11 rounded-pill">
                    {{ $contactMessage->subject ?: 'General Inquiry' }}
                </span>
            </div>
            <ul class="breadcrumb mb-0 mt-1 fs-12 text-muted">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.contact-messages.index') }}" class="text-muted text-decoration-none">Contact Inquiries</a></li>
                <li class="breadcrumb-item active text-dark">#{{ $contactMessage->id }}</li>
            </ul>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-sm btn-light border d-flex align-items-center gap-1 shadow-2xs">
                <i class="feather-arrow-left"></i> <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 px-3 mb-3 border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
                <i class="feather-check-circle text-success fs-5"></i>
                <div class="fs-13">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show rounded-3 py-2 px-3 mb-3 border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
                <i class="feather-alert-triangle text-warning fs-5"></i>
                <div class="fs-13">{{ session('warning') }}</div>
                <button type="button" class="btn-close ms-auto py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 px-3 mb-3 border-0 shadow-sm" role="alert">
                <div class="fw-bold fs-13 mb-1">Please fix the following:</div>
                <ul class="mb-0 ps-3 fs-12">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3">
            <!-- Left Column: Customer Message + Email Reply Box + Conversation History -->
            <div class="col-lg-8">
                <!-- Customer Message Card -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body p-3 p-sm-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-3 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-circle fw-bold fs-5 flex-shrink-0">
                                    {{ strtoupper(substr($contactMessage->first_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $contactMessage->full_name }}</h6>
                                        @if($contactMessage->user_id)
                                            <span class="badge bg-soft-success text-success fs-10 px-2 py-0 rounded-pill">Registered User</span>
                                        @else
                                            <span class="badge bg-soft-secondary text-secondary fs-10 px-2 py-0 rounded-pill">Guest</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mt-1 fs-12 text-muted flex-wrap">
                                        <a href="mailto:{{ $contactMessage->email }}" class="text-primary text-decoration-none d-flex align-items-center gap-1">
                                            <i class="feather-mail"></i> {{ $contactMessage->email }}
                                        </a>
                                        @if($contactMessage->phone)
                                            <a href="tel:{{ $contactMessage->phone }}" class="text-secondary text-decoration-none d-flex align-items-center gap-1">
                                                <i class="feather-phone"></i> {{ $contactMessage->phone }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="fs-12 text-muted">
                                <i class="feather-clock me-1"></i>{{ $contactMessage->created_at->format('M d, Y • h:i A') }}
                                <span class="d-block text-end fs-11 text-muted">{{ $contactMessage->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Inquiry Message Body -->
                        <div class="p-3 bg-light rounded-3 border border-start-3 border-primary">
                            <div class="fs-11 fw-bold text-muted text-uppercase mb-1 letter-spacing-1">Customer Inquiry:</div>
                            <div class="text-dark fs-14 lh-base">
                                {!! nl2br(e($contactMessage->message)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Email Reply Card -->
                <div class="card border-0 shadow-sm rounded-3 mb-3 border-top-primary" id="emailReplyCard">
                    <div class="card-header bg-white py-2 px-3 d-flex align-items-center justify-content-between border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="feather-send text-primary fs-5"></i>
                            <h6 class="mb-0 fw-bold fs-14">Send Email Response</h6>
                        </div>
                        <span class="fs-12 text-muted">To: <strong class="text-dark">{{ $contactMessage->email }}</strong></span>
                    </div>
                    <div class="card-body p-3 p-sm-4">
                        <form action="{{ route('admin.contact-messages.reply', $contactMessage->id) }}" method="POST">
                            @csrf

                            <div class="mb-2">
                                <label class="form-label fw-bold fs-12 text-dark mb-1">Email Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control form-control-sm"
                                       value="{{ old('subject', 'Re: ' . ($contactMessage->subject ?: 'Your inquiry to ' . config('app.name'))) }}" required>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label fw-bold fs-12 text-dark mb-0">Reply Message <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2 fs-11" onclick="insertReplyTemplate('greeting')">Hello</button>
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2 fs-11" onclick="insertReplyTemplate('received')">Inquiry Received</button>
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2 fs-11" onclick="insertReplyTemplate('resolved')">Resolved</button>
                                    </div>
                                </div>
                                <textarea name="reply_message" id="adminReplyTextarea" class="form-control form-control-sm" rows="5"
                                          placeholder="Type your official email reply here..." required>{{ old('reply_message') }}</textarea>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-1">
                                <div class="fs-11 text-muted">
                                    From: <span class="text-dark fw-semibold">{{ auth('admin')->user()->name ?? 'Admin Support' }}</span>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary px-3 d-flex align-items-center gap-1">
                                    <span>Send Email Reply</span>
                                    <i class="feather-send"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sent Email History (Timeline) -->
                @if($contactMessage->replies->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-header bg-white py-2 px-3 d-flex align-items-center justify-content-between border-bottom">
                            <h6 class="mb-0 fw-bold fs-14 d-flex align-items-center gap-2">
                                <i class="feather-mail text-success"></i>
                                Email Replies ({{ $contactMessage->replies->count() }})
                            </h6>
                            <span class="fs-11 text-muted">Sent to customer</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline-email-list">
                                @foreach($contactMessage->replies as $reply)
                                    <div class="p-3 mb-2 rounded-3 border bg-white shadow-2xs {{ $reply->status === 'sent' ? 'border-success-subtle' : 'border-danger-subtle' }}">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 mb-2 border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-text avatar-xs bg-primary text-white rounded-circle fs-11">
                                                    {{ strtoupper(substr($reply->admin?->name ?? 'A', 0, 1)) }}
                                                </div>
                                                <div class="fs-12">
                                                    <span class="fw-bold text-dark">{{ $reply->admin?->name ?? 'Admin Support' }}</span>
                                                    <span class="text-muted">➔ {{ $reply->email_to }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($reply->status === 'sent')
                                                    <span class="badge bg-soft-success text-success fs-10 px-2 py-0">
                                                        <i class="feather-check"></i> Sent
                                                    </span>
                                                @else
                                                    <span class="badge bg-soft-danger text-danger fs-10 px-2 py-0" title="{{ $reply->error_message }}">
                                                        <i class="feather-alert-circle"></i> Failed
                                                    </span>
                                                @endif
                                                <span class="fs-11 text-muted">{{ $reply->created_at->format('M d, Y • h:i A') }}</span>
                                            </div>
                                        </div>

                                        <div class="fw-semibold fs-12 text-dark mb-1">
                                            Subject: {{ $reply->subject }}
                                        </div>
                                        <div class="fs-13 text-secondary lh-base">
                                            {!! nl2br(e($reply->message)) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Status & Internal Notes & Metadata (Compact) -->
            <div class="col-lg-4">
                <!-- Status & Internal Notes -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-white py-2 px-3 border-bottom">
                        <h6 class="mb-0 fw-bold fs-14">Status & Staff Notes</h6>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('admin.contact-messages.update', $contactMessage->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fs-12 fw-bold text-dark mb-1">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="unread" {{ $contactMessage->status === 'unread' ? 'selected' : '' }}>🔴 Unread</option>
                                    <option value="read" {{ $contactMessage->status === 'read' ? 'selected' : '' }}>🔵 Read</option>
                                    <option value="replied" {{ $contactMessage->status === 'replied' ? 'selected' : '' }}>🟢 Replied</option>
                                    <option value="closed" {{ $contactMessage->status === 'closed' ? 'selected' : '' }}>⚪ Closed</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-12 fw-bold text-dark mb-1">Staff Internal Notes</label>
                                <textarea name="admin_notes" class="form-control form-control-sm" rows="3"
                                          placeholder="Add private staff notes...">{{ old('admin_notes', $contactMessage->admin_notes) }}</textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                                    <i class="feather-save me-1"></i> Save Status
                                </button>
                            </div>
                        </form>

                        <div class="border-top pt-2 mt-3 text-end">
                            <form action="{{ route('admin.contact-messages.destroy', $contactMessage->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this inquiry?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-link text-danger text-decoration-none p-0">
                                    <i class="feather-trash-2 me-1"></i> Delete Inquiry
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Inquiry Metadata Box -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-2 px-3 border-bottom">
                        <h6 class="mb-0 fw-bold fs-14">Inquiry Metadata</h6>
                    </div>
                    <div class="card-body p-3 fs-12">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">ID:</span>
                            <span class="fw-bold">#{{ $contactMessage->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Account:</span>
                            <span>
                                @if($contactMessage->user_id)
                                    <a href="{{ route('admin.users.edit', $contactMessage->user_id) }}" class="text-primary fw-semibold text-decoration-none">
                                        {{ $contactMessage->user?->name ?? 'User #' . $contactMessage->user_id }}
                                    </a>
                                @else
                                    <span class="text-muted">Guest Visitor</span>
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">IP Address:</span>
                            <span>{{ $contactMessage->ip_address ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Received:</span>
                            <span>{{ $contactMessage->created_at->format('M d, Y • h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-top-primary {
        border-top: 3px solid #ff8d2f !important;
    }
    .shadow-2xs {
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }
    .border-start-3 {
        border-left-width: 3px !important;
    }
</style>

<script>
    function insertReplyTemplate(type) {
        const textarea = document.getElementById('adminReplyTextarea');
        if (!textarea) return;

        const customerName = '{{ addslashes($contactMessage->first_name) }}';
        let text = '';

        if (type === 'greeting') {
            text = `Dear ${customerName},\n\nThank you for reaching out to us. `;
        } else if (type === 'received') {
            text = `Dear ${customerName},\n\nWe have received your inquiry and our support team is currently looking into it. We will get back to you shortly.\n\nBest regards,\nSupport Team`;
        } else if (type === 'resolved') {
            text = `Dear ${customerName},\n\nWe have reviewed your request and the issue has been resolved. Please let us know if you need any further assistance.\n\nThank you for choosing us!\nSupport Team`;
        }

        textarea.value = text;
        textarea.focus();
    }
</script>
@endsection
