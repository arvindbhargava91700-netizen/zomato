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

<style>
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

<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">{{ $ticket->ticket_id }} &middot; {{ \Illuminate\Support\Str::limit($ticket->subject, 40) }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route($rp . 'index') }}">My Tickets</a></li>
                <li class="breadcrumb-item">{{ $ticket->ticket_id }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <span class="badge {{ $statusBadge[$ticket->status] }} fs-6">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
            <span class="badge {{ $priorityBadge[$ticket->priority] }} fs-6">{{ ucfirst($ticket->priority) }}</span>
            <a href="{{ route($rp . 'index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
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

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3">
                        <h6 class="card-title mb-0 fw-bold">Ticket Details</h6>
                    </div>
                    <div class="card-body small">
                        <p class="mb-1"><b>Category:</b> {{ \App\Models\Ticket::CATEGORIES[$ticket->category] ?? $ticket->category }}</p>
                        <p class="mb-1"><b>Order ID:</b> {{ $ticket->order_id ?? 'N/A' }}</p>
                        <p class="mb-1"><b>Raised by:</b> {{ ucfirst($ticket->user_type) }}</p>
                        <p class="mb-1"><b>Created:</b> {{ $ticket->created_at->format('d M Y, h:i A') }}</p>
                        @if($ticket->resolution_note)
                            <div class="alert alert-light border mt-2 mb-0">
                                <b>Resolution:</b><br>{{ $ticket->resolution_note }}
                            </div>
                        @endif
                        @if($ticket->attachment)
                            <p class="mt-2 mb-0"><a href="{{ $asset($ticket->attachment) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View Attachment</a></p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3">
                        <h6 class="card-title mb-0 fw-bold">Conversation</h6>
                    </div>
                    <div class="card-body" id="chatBox" style="max-height: 420px; overflow-y: auto;">
                        @php $lastDay = null; @endphp
                        @foreach($messages as $msg)
                            @php $dayLabel = $msg->created_at->format('d M Y'); @endphp
                            @if($dayLabel !== $lastDay)
                                <div class="chat-day-admin"><span>{{ $msg->created_at->format('d M Y') }}</span></div>
                                @php $lastDay = $dayLabel; @endphp
                            @endif
                            <div class="d-flex mb-3 {{ $msg->sender_type == 'user' ? 'justify-content-end' : '' }}" data-msg-id="{{ $msg->id }}">
                                <div class="px-3 py-2 rounded-3 {{ $msg->sender_type == 'admin' ? 'bg-danger text-white' : 'bg-light' }}" style="max-width: 80%;">
                                    <div class="small {{ $msg->sender_type == 'admin' ? 'text-white-50' : 'text-muted' }}">
                                        {{ $msg->sender_type == 'admin' ? 'Support Team' : $ticket->raiserName() }} &middot; {{ $msg->created_at->format('d M, h:i A') }}
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
                        @if($ticket->status != 'closed')
                            <form method="POST" action="{{ route($rp . 'reply', $ticket->ticket_id) }}" enctype="multipart/form-data" class="mb-2 ticket-chat-reply">
                                @csrf
                                <div class="input-group">
                                    <textarea name="message" class="form-control" rows="1" placeholder="Type your reply..." required></textarea>
                                    <button class="btn text-white fw-semibold ticket-send-btn" style="background-color: #cb202d; border: none;" type="submit"><i class="ri-send-plane-line me-1"></i> Send</button>
                                </div>
                                <div class="mt-1">
                                    <input type="file" name="attachment" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                            </form>
                        @endif

                        @if($ticket->status == 'resolved')
                            <div class="alert alert-success d-flex align-items-center justify-content-between mb-0">
                                <span>Is your issue resolved?</span>
                                <span>
                                    <form method="POST" action="{{ route($rp . 'close', $ticket->ticket_id) }}" class="d-inline">
                                        @csrf<button class="btn btn-sm btn-success">Satisfied</button>
                                    </form>
                                    <form method="POST" action="{{ route($rp . 'reopen', $ticket->ticket_id) }}" class="d-inline">
                                        @csrf<button class="btn btn-sm btn-outline-danger">Not Satisfied</button>
                                    </form>
                                </span>
                            </div>
                        @endif

                        @if($ticket->status == 'closed' && !$ticket->rating)
                            <form method="POST" action="{{ route($rp . 'rate', $ticket->ticket_id) }}" class="d-flex align-items-center gap-2">
                                @csrf
                                <span class="small">Rate our support:</span>
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="submit" name="rating" value="{{ $i }}" class="btn btn-sm btn-outline-warning px-2" title="{{ $i }} star">{{ $i }} ★</button>
                                @endfor
                            </form>
                        @endif

                        @if($ticket->status == 'closed' && $ticket->rating)
                            <div class="text-end text-warning">Your rating: {{ $ticket->rating }} ★</div>
                        @endif
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
    var myName = @json($ticket->raiserName());
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

        var isMine = data.sender_type !== 'admin';
        var who = isMine ? myName : 'Support Team';
        var wrap = document.createElement('div');
        wrap.className = 'd-flex mb-3 ' + (isMine ? 'justify-content-end' : '');
        wrap.setAttribute('data-msg-id', data.id);

        var html = '';
        html += '<div class="px-3 py-2 rounded-3 chat-bubble-admin ' + (isMine ? 'bg-light' : 'bg-danger text-white') + '" style="max-width: 80%;">';
        html += '<div class="small ' + (isMine ? 'text-muted' : 'text-white-50') + '">' + esc(who) + ' &middot; ' + esc(data.created_at) + '</div>';
        if (data.message) {
            html += '<div class="mt-1">' + esc(data.message).replace(/\n/g, '<br>') + '</div>';
        }
        if (data.attachment) {
            html += '<a href="' + data.attachment + '" target="_blank" class="' + (isMine ? '' : 'text-white text-decoration-underline') + ' small"><i class="ri-attachment-2 me-1"></i>' + esc(data.attachment_name) + '</a>';
        }
        html += '</div>';

        wrap.innerHTML = html;
        box.appendChild(wrap);
        box.scrollTop = box.scrollHeight;
    }

    var form = document.querySelector('.ticket-chat-reply');
    if (form) {
        var btn = form.querySelector('.ticket-send-btn');
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
                        sender_type: 'user',
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
