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
    $asset = fn($p) => $p ? asset('storage/' . $p) : null;
@endphp

@extends('layouts.admin.main')
@section('title', getPageTitle('Ticket Details'))
<style>
    /* =========================================
   SIMPLE TICKET CARD
========================================= */

.simple-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 18px;
    background: #ffffff;
    border-bottom: 1px solid #eeeeee;
}

/* Header Icon */
.simple-section-header .header-icon {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 9px;
    background: #fff1f2;
    color: #cb202d;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 17px;
}

/* Header Title */
.simple-section-header h6 {
    font-size: 14px;
    font-weight: 700;
    color: #252a31;
}

/* Header Subtitle */
.simple-section-header small {
    display: block;
    margin-top: 2px;
    font-size: 11px;
    color: #9299a1;
}

/* Update Header */
.update-header {
    background: #fafafa;
    border-top: 1px solid #eeeeee;
}


/* =========================================
   TICKET INFORMATION
========================================= */

.info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 11px 0;

    border-bottom: 1px solid #f1f1f1;
}

.info-label {
    font-size: 12px;
    color: #7b838c;
}

.info-value {
    font-size: 12px;
    font-weight: 600;
    color: #343a40;
}


/* =========================================
   RESOLUTION
========================================= */

.resolution-note {
    margin-top: 14px;
    padding: 12px;

    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
}

.resolution-title {
    display: flex;
    align-items: center;
    gap: 6px;

    margin-bottom: 6px;

    font-size: 12px;
    font-weight: 700;
    color: #343a40;
}

.resolution-title i {
    color: #cb202d;
    font-size: 15px;
}

.resolution-text {
    font-size: 12px;
    line-height: 1.5;
    color: #6c757d;
}


/* =========================================
   ATTACHMENT
========================================= */

.attachment-btn {
    display: flex;
    align-items: center;

    width: 100%;
    padding: 9px 11px;

    border: 1px solid #e5e7eb;
    border-radius: 7px;

    background: #fff;

    color: #495057;
    font-size: 12px;
    font-weight: 600;

    text-decoration: none;

    transition: all .2s ease;
}

.attachment-btn > i:first-child {
    color: #cb202d;
    font-size: 15px;
    margin-right: 7px;
}

.attachment-btn:hover {
    color: #cb202d;
    border-color: #cb202d;
    background: #fff8f9;
}


/* =========================================
   FORM
========================================= */

.simple-label {
    font-size: 12px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 6px;
}

.simple-input {
    border: 1px solid #dee2e6;
    border-radius: 7px;
    font-size: 12px;
    color: #343a40;
}

.simple-input:focus {
    border-color: #cb202d;
    box-shadow: 0 0 0 2px rgba(203, 32, 45, .08);
}


/* =========================================
   UPDATE BUTTON
========================================= */

.update-btn {
    width: 100%;
    height: 38px;

    border: none;
    border-radius: 7px;

    background: #cb202d;
    color: #ffffff;

    font-size: 12px;
    font-weight: 600;

    transition: all .2s ease;
}

.update-btn:hover {
    background: #b51c28;
    transform: translateY(-1px);
}

.update-btn:active {
    transform: translateY(0);
}


/* =========================================
   CONVERSATION DAY SEPARATOR + SPINNER
========================================= */

.chat-day-admin {
    text-align: center;
    margin: 10px 0 14px;
    position: relative;
}

.chat-day-admin span {
    display: inline-block;
    padding: 3px 12px;
    font-size: 11px;
    font-weight: 600;
    color: #6c757d;
    background: #f1f3f5;
    border-radius: 30px;
}

.chat-bubble-admin {
    transition: box-shadow .15s ease, transform .15s ease;
}

.chat-bubble-admin:hover {
    box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
}

.spin {
    animation: chatSpin 0.8s linear infinite;
    display: inline-block;
}

@keyframes chatSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

</style>
@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">{{ $ticket->ticket_id }} &middot; {{ \Illuminate\Support\Str::limit($ticket->subject, 40) }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Support Tickets</a></li>
                <li class="breadcrumb-item">{{ $ticket->ticket_id }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <span class="badge {{ $statusBadge[$ticket->status] }} fs-6">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
            <span class="badge {{ $priorityBadge[$ticket->priority] }} fs-6">{{ ucfirst($ticket->priority) }}</span>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>@endif

        <div class="row g-3">
       <!-- Details + Status update -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-3">

                <!-- ================= Ticket Details ================= -->
                <div class="simple-section-header">
                    <div class="header-icon">
                        <i class="ri-ticket-2-line"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Ticket Details</h6>
                        <small>Ticket information</small>
                    </div>
                </div>

                <div class="card-body px-3 py-3">

                    <div class="info-row">
                        <span class="info-label">Category</span>
                        <span class="info-value">
                            {{ \App\Models\Ticket::CATEGORIES[$ticket->category] ?? $ticket->category }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Order ID</span>
                        <span class="info-value">
                            {{ $ticket->order_id ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Raised By</span>
                        <span class="info-value text-end">
                            {{ $ticket->user?->name ?? ucfirst($ticket->user_type) }}
                            <small class="d-block text-muted">
                                {{ ucfirst($ticket->user_type) }} #{{ $ticket->user_id }}
                            </small>
                        </span>
                    </div>

                    <div class="info-row border-0">
                        <span class="info-label">Created</span>
                        <span class="info-value">
                            {{ $ticket->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>

                    @if($ticket->resolution_note)
                        <div class="resolution-note">
                            <div class="resolution-title">
                                <i class="ri-chat-check-line"></i>
                                Resolution
                            </div>
                            <div class="resolution-text">
                                {{ $ticket->resolution_note }}
                            </div>
                        </div>
                    @endif

                    @if($ticket->attachment)
                        <div class="mt-3">
                            <a href="{{ $asset($ticket->attachment) }}"
                            target="_blank"
                            class="attachment-btn">
                                <i class="ri-attachment-2"></i>
                                View Attachment
                                <i class="ri-arrow-right-line ms-auto"></i>
                            </a>
                        </div>
                    @endif

                </div>


                <!-- ================= Update Ticket ================= -->
                <div class="simple-section-header update-header">
                    <div class="header-icon">
                        <i class="ri-edit-2-line"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Update Ticket</h6>
                        <small>Change ticket details</small>
                    </div>
                </div>

                <div class="card-body px-3 py-3">

                    <form method="POST"
                        action="{{ route('admin.tickets.status', $ticket->ticket_id) }}">
                        @csrf

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label simple-label">
                                Status
                            </label>

                            <select name="status"
                                    class="form-select form-select-sm simple-input">
                                @foreach(['open','in_progress','resolved','closed','reopened'] as $s)
                                    <option value="{{ $s }}"
                                        {{ $ticket->status == $s ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_',' ',$s)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <label class="form-label simple-label">
                                Priority
                            </label>

                            <select name="priority"
                                    class="form-select form-select-sm simple-input">
                                @foreach(['low','medium','high','urgent'] as $p)
                                    <option value="{{ $p }}"
                                        {{ $ticket->priority == $p ? 'selected' : '' }}>
                                        {{ ucfirst($p) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Resolution -->
                        <div class="mb-3">
                            <label class="form-label simple-label">
                                Resolution Note
                            </label>

                            <textarea name="resolution_note"
                                    class="form-control form-control-sm simple-input"
                                    rows="3"
                                    placeholder="Enter resolution note...">{{ $ticket->resolution_note }}</textarea>
                        </div>

                        <!-- Update Button -->
                        <button type="submit"
                                class="update-btn">
                            <i class="ri-save-3-line me-1"></i>
                            Update Ticket
                        </button>

                    </form>

                </div>

            </div>
        </div>
 
            <!-- Conversation -->
            <div class="col-lg-8">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3"><h6 class="card-title mb-0 fw-bold">Conversation</h6></div>
                    <div class="card-body" id="chatBox" style="max-height: 460px; overflow-y: auto;">
                        @php $lastDay = null; @endphp
                        @foreach($messages as $msg)
                            @php $dayLabel = $msg->created_at->format('d M Y'); @endphp
                            @if($dayLabel !== $lastDay)
                                <div class="chat-day-admin"><span>{{ $msg->created_at->format('d M Y') }}</span></div>
                                @php $lastDay = $dayLabel; @endphp
                            @endif
                            <div class="d-flex mb-3 {{ $msg->sender_type == 'admin' ? 'justify-content-end' : '' }}" data-msg-id="{{ $msg->id }}">
                                <div class="px-3 py-2 rounded-3 {{ $msg->sender_type == 'admin' ? 'bg-danger text-white' : 'bg-light' }}" style="max-width: 80%;">
                                    <div class="small {{ $msg->sender_type == 'admin' ? 'text-white-50' : 'text-muted' }}">
                                        {{ $msg->sender_type == 'admin' ? 'Support Team' : ($ticket->user?->name ?? ucfirst($ticket->user_type)) }} &middot; {{ $msg->created_at->format('d M, h:i A') }}
                                    </div>
                                    @if($msg->message)<div class="mt-1">{!! nl2br(e($msg->message)) !!}</div>@endif
                                    @if($msg->attachment)
                                        <a href="{{ $asset($msg->attachment) }}" target="_blank" class="{{ $msg->sender_type == 'admin' ? 'text-white text-decoration-underline' : '' }} small">View attachment</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white">
                        <form class="admin-chat-reply" method="POST" action="{{ route('admin.tickets.reply', $ticket->ticket_id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <textarea name="message" class="form-control" rows="1" placeholder="Type your reply..." required></textarea>
                                <button class="btn text-white fw-semibold admin-send-btn" style="background-color: #cb202d; border: none;" type="submit"><i class="ri-send-plane-line me-1"></i> Send</button>
                            </div>
                            <div class="mt-1">
                                <input type="file" name="attachment" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ticketId = "{{ $ticket->ticket_id }}";
    var customerName = @json($ticket->user?->name ?? ucfirst($ticket->user_type));
    var box = document.getElementById('chatBox');

    function esc(s) {
        var d = document.createElement('div');
        d.textContent = s == null ? '' : s;
        return d.innerHTML;
    }

    var lastDay = null;
    if (box) {
        var days = box.querySelectorAll('.chat-day-admin');
        if (days.length) {
            lastDay = days[days.length - 1].querySelector('span').textContent;
        }
    }

    function appendDay(label) {
        if (!box) return;
        var days = box.querySelectorAll('.chat-day-admin');
        if (days.length && days[days.length - 1].querySelector('span').textContent === label) return;
        var d = document.createElement('div');
        d.className = 'chat-day-admin';
        d.innerHTML = '<span>' + esc(label) + '</span>';
        box.appendChild(d);
    }

    function appendMessage(data) {
        if (!box || !data || !data.id) return;
        if (box.querySelector('[data-msg-id="' + data.id + '"]')) return;

        if (data.created_date_label && data.created_date_label !== lastDay) {
            lastDay = data.created_date_label;
            appendDay(data.created_date_label);
        }

        var isAdmin = data.sender_type === 'admin';
        var who = isAdmin ? 'Support Team' : customerName;
        var wrap = document.createElement('div');
        wrap.className = 'd-flex mb-3 ' + (isAdmin ? 'justify-content-end' : '');
        wrap.setAttribute('data-msg-id', data.id);

        var html = '';
        html += '<div class="px-3 py-2 rounded-3 chat-bubble-admin ' + (isAdmin ? 'bg-danger text-white' : 'bg-light') + '" style="max-width: 80%;">';
        html += '<div class="small ' + (isAdmin ? 'text-white-50' : 'text-muted') + '">' + esc(who) + ' &middot; ' + esc(data.created_at) + '</div>';
        if (data.message) {
            html += '<div class="mt-1">' + esc(data.message).replace(/\n/g, '<br>') + '</div>';
        }
        if (data.attachment) {
            html += '<a href="' + data.attachment + '" target="_blank" class="' + (isAdmin ? 'text-white text-decoration-underline' : '') + ' small"><i class="ri-attachment-2 me-1"></i>' + esc(data.attachment_name) + '</a>';
        }
        html += '</div>';

        wrap.innerHTML = html;
        box.appendChild(wrap);
        box.scrollTop = box.scrollHeight;
    }

    var form = document.querySelector('.admin-chat-reply');
    if (form) {
        var btn = form.querySelector('.admin-send-btn');
        var defaultBtn = btn ? btn.innerHTML : 'Send';

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (btn && btn.disabled) return;

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="ri-loader-4-line spin me-1"></i> Sending…';
            }

            var fd = new FormData(form);
            fetch(form.getAttribute('action'), {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res && res.message) appendMessage(res.message);
                form.reset();
            })
            .catch(function () {
                var ta = form.querySelector('textarea[name="message"]');
                if (ta && ta.value.trim() !== '') {
                    var now = new Date();
                    appendMessage({
                        id: 'local-' + now.getTime(),
                        ticket_id: ticketId,
                        sender_type: 'admin',
                        sender_id: null,
                        message: ta.value,
                        attachment: null,
                        attachment_name: null,
                        attachment_is_image: false,
                        created_at: now.toLocaleString('en-IN', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }),
                        created_date: '',
                        created_date_label: now.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
                    });
                }
                form.reset();
            })
            .finally(function () {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = defaultBtn;
                }
            });
        });
    }

    if (window.Echo) {
        window.Echo.channel('ticket.' + ticketId)
            .listen('.ticket.message', function (data) {
                appendMessage(data);
            });
    }
});
</script>
@endpush

@endsection
