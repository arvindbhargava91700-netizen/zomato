@extends('layouts.front.main')

@php
    $statusMeta = [
        'open'         => ['label' => 'Open',         'class' => 'bg-soft-primary text-primary'],
        'in_progress'  => ['label' => 'In Progress',  'class' => 'bg-soft-info text-info'],
        'pending'      => ['label' => 'Pending',      'class' => 'bg-soft-warning text-warning'],
        'resolved'     => ['label' => 'Resolved',     'class' => 'bg-soft-success text-success'],
        'closed'       => ['label' => 'Closed',       'class' => 'bg-soft-secondary text-secondary'],
        'rejected'     => ['label' => 'Rejected',     'class' => 'bg-soft-danger text-danger'],
    ];
    $status = $statusMeta[$ticket->status] ?? ['label' => ucwords(str_replace('_', ' ', $ticket->status)), 'class' => 'bg-soft-primary text-primary'];

    $priorityMeta = [
        'low'      => 'bg-soft-secondary text-secondary',
        'medium'   => 'bg-soft-info text-info',
        'high'     => 'bg-soft-warning text-warning',
        'urgent'   => 'bg-soft-danger text-danger',
    ];
    $priorityClass = $priorityMeta[$ticket->priority] ?? 'bg-soft-info text-info';
@endphp

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
                <h2 class="h3 mb-0 text-white">{{ $ticket->ticket_id }}</h2>
                <span class="badge {{ $status['class'] }} fs-6 px-3 py-2 rounded-pill text-capitalize">{{ $status['label'] }}</span>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i> Home</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('help') }}">Support</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tickets-index') }}">My Tickets</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $ticket->ticket_id }}</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <section class="section-b-space">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <!-- details -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3 sticky-lg-top ticket-detail-card">
                        <div class="ticket-cover">
                            <div class="ticket-cover-icon"><i class="ri-ticket-2-line"></i></div>
                            <div class="ticket-cover-text">
                                <div class="ticket-cover-id">{{ $ticket->ticket_id }}</div>
                                <div class="ticket-cover-sub">Ticket Details</div>
                            </div>
                            <span class="ticket-status-badge {{ $status['class'] }} text-capitalize">{{ $status['label'] }}</span>
                        </div>
                        <div class="card-body">
                            <div class="ticket-subject mb-3">{{ $ticket->subject }}</div>

                            <ul class="ticket-info list-unstyled mb-0">
                                <li>
                                    <span class="ti-icon"><i class="ri-folder-line"></i></span>
                                    <div class="ti-text">
                                        <span class="ti-label">Category</span>
                                        <span class="ti-value">{{ \App\Models\Ticket::CATEGORIES[$ticket->category] ?? ucfirst($ticket->category) }}</span>
                                    </div>
                                </li>
                                <li>
                                    <span class="ti-icon"><i class="ri-hashtag"></i></span>
                                    <div class="ti-text">
                                        <span class="ti-label">Order ID</span>
                                        <span class="ti-value">{{ $ticket->order_id ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li>
                                    <span class="ti-icon"><i class="ri-flag-line"></i></span>
                                    <div class="ti-text">
                                        <span class="ti-label">Priority</span>
                                        <span class="badge {{ $priorityClass }} text-capitalize">{{ $ticket->priority }}</span>
                                    </div>
                                </li>
                                <li>
                                    <span class="ti-icon"><i class="ri-calendar-line"></i></span>
                                    <div class="ti-text">
                                        <span class="ti-label">Created</span>
                                        <span class="ti-value">{{ $ticket->created_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                </li>
                            </ul>

                            @if($ticket->attachment)
                                <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                                    class="btn btn-sm btn-outline-secondary w-100 mt-3 ticket-attach-btn">
                                    <i class="ri-attachment-2 me-1"></i> View Attachment
                                </a>
                            @endif

                            @if($ticket->resolution_note)
                                <div class="mt-3 p-3 rounded-3 resolution-note">
                                    <div class="fw-semibold mb-1">
                                        <i class="ri-check-double-line text-success me-1"></i> Resolution
                                    </div>
                                    <div class="small">{{ $ticket->resolution_note }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- conversation -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm conversation-card">
                        <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                            <span class="conv-icon"><i class="ri-chat-3-line"></i></span>
                            <div class="conv-head-text">
                                <div class="fw-bold lh-1">Conversation</div>
                                <div class="small text-muted d-flex align-items-center gap-1 mt-1">
                                    <span class="online-dot"></span> Support team usually replies within a few hours
                                </div>
                            </div>
                            <span class="badge bg-soft-secondary text-secondary ms-auto conversation-count">{{ $messages->count() }} messages</span>
                        </div>
                        <div class="card-body">
                            <div class="chat-box" id="chatBox">
                                @php $lastDay = null; @endphp
                                @forelse($messages as $msg)
                                    @php
                                        $isAdmin = $msg->sender_type === 'admin';
                                        $day = $msg->created_at->format('Y-m-d');
                                        $showDay = $day !== $lastDay;
                                        $lastDay = $day;
                                    @endphp
                                    @if($showDay)
                                        <div class="chat-day"><span>{{ $msg->created_at->format('d M Y') }}</span></div>
                                    @endif
                                    <div class="d-flex mb-3 {{ $isAdmin ? 'justify-content-start' : 'justify-content-end' }}" data-msg-id="{{ $msg->id }}">
                                        @if($isAdmin)
                                            <div class="chat-avatar chat-avatar--admin me-2">
                                                <i class="ri-customer-service-2-line"></i>
                                            </div>
                                        @endif
                                        <div class="chat-bubble {{ $isAdmin ? 'chat-bubble-admin' : 'chat-bubble-user' }}">
                                            <div class="chat-meta">
                                                <span class="chat-sender">
                                                    <span class="chat-dot"></span>{{ $isAdmin ? 'Support Team' : 'You' }}
                                                </span>
                                                <span class="chat-time">{{ $msg->created_at->format('d M, h:i A') }}</span>
                                            </div>
                                            <div class="chat-text">{{ $msg->message }}</div>
                                            @if($msg->attachment)
                                                @php
                                                    $ext = strtolower(pathinfo($msg->attachment, PATHINFO_EXTENSION));
                                                    $isImage = in_array($ext, ['jpg','jpeg','png']);
                                                @endphp
                                                <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank"
                                                    class="chat-file-chip {{ $isAdmin ? 'chat-file-chip--admin' : 'chat-file-chip--user' }}">
                                                    <span class="chat-file-ic"><i class="ri-{{ $isImage ? 'image' : 'file' }}-line"></i></span>
                                                    <span class="chat-file-name text-truncate">{{ basename($msg->attachment) }}</span>
                                                    <i class="ri-download-line chat-file-dl"></i>
                                                </a>
                                            @endif
                                        </div>
                                        @if(!$isAdmin)
                                            <div class="chat-avatar chat-avatar--user ms-2">
                                                <i class="ri-user-line"></i>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-5">
                                        <i class="ri-chat-off-line fs-1 d-block mb-2 text-light"></i>
                                        No messages yet.
                                    </div>
                                @endforelse
                            </div>

                            <!-- reply -->
                            @if(!$ticket->isClosed())
                                <form method="POST" action="{{ route('tickets-reply', $ticket->ticket_id) }}"
                                    enctype="multipart/form-data" class="mt-3 chat-reply">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="message" rows="3" class="form-control chat-input" placeholder="Type your reply..." required></textarea>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <label class="btn chat-attach-btn mb-0 cursor-pointer">
                                            <i class="ri-attachment-2 me-1"></i> Attach
                                            <input type="file" name="attachment" class="d-none" accept=".jpg,.jpeg,.png,.pdf">
                                        </label>
                                        <button type="submit" class="btn theme-btn ms-auto">
                                            <i class="ri-send-plane-line me-1"></i> Send
                                        </button>
                                    </div>
                                </form>

                                @if($ticket->status === 'resolved')
                                    <div class="mt-3 p-3 rounded-3 border resolution-prompt">
                                        <span class="fw-semibold d-block mb-2">Was this resolved to your satisfaction?</span>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <form method="POST" action="{{ route('tickets-close', $ticket->ticket_id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="ri-check-line me-1"></i> Yes, Close
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('tickets-reopen', $ticket->ticket_id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                                    <i class="ri-refresh-line me-1"></i> No, Reopen
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-secondary mt-3 mb-0 d-flex align-items-center gap-2">
                                    <i class="ri-lock-line"></i> This ticket is closed. Thank you for contacting us.
                                </div>
                                @if(!$ticket->rating)
                                    <form method="POST" action="{{ route('tickets-rate', $ticket->ticket_id) }}" class="mt-3 rating-form">
                                        @csrf
                                        <label class="form-label fw-semibold">Rate our support:</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <select name="rating" class="form-select" style="max-width:120px;" required>
                                                <option value="">--</option>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}">{{ $i }} ★</option>
                                                @endfor
                                            </select>
                                            <button type="submit" class="btn theme-btn btn-sm">Submit</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="mt-3 rated-badge">
                                        <i class="ri-star-fill"></i> You rated this ticket {{ $ticket->rating }}/5
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
            .bg-soft-primary { background: rgba(13,110,253,.12); }
            .bg-soft-info { background: rgba(13,202,240,.12); }
            .bg-soft-warning { background: rgba(255,193,7,.14); }
            .bg-soft-success { background: rgba(25,135,84,.12); }
            .bg-soft-danger { background: rgba(220,53,69,.12); }
            .bg-soft-secondary { background: rgba(108,117,125,.14); }

            .text-theme { color: #ff8d2f; }

            /* === Ticket details card === */
            .ticket-detail-card { border-radius: 16px; overflow: hidden; }
            .ticket-cover {
                background: linear-gradient(135deg, #ff8d2f 0%, #ff6a3d 100%);
                color: #fff; padding: 18px 20px; display: flex; align-items: center; gap: 12px;
            }
            .ticket-cover-icon {
                width: 46px; height: 46px; min-width: 46px; border-radius: 13px;
                display: flex; align-items: center; justify-content: center;
                background: rgba(255,255,255,.2); color: #fff; font-size: 1.4rem;
            }
            .ticket-cover-id { font-weight: 700; font-size: 1.05rem; line-height: 1.1; }
            .ticket-cover-sub { font-size: .75rem; opacity: .85; }
            .ticket-status-badge {
                margin-left: auto; padding: .35rem .8rem; border-radius: 999px;
                font-weight: 600; font-size: .8rem; background: rgba(255,255,255,.22) !important;
                color: #fff !important; white-space: nowrap;
            }
            .ticket-subject {
                font-weight: 600; font-size: .98rem; color: #222;
                padding: 14px 16px; background: #fafafa; border-radius: 12px; border-left: 4px solid #ff8d2f;
            }
            .ticket-info li { display: flex; align-items: center; gap: 12px; padding: 11px 0; border-bottom: 1px dashed #eee; }
            .ticket-info li:last-child { border-bottom: 0; }
            .ti-icon {
                width: 34px; height: 34px; min-width: 34px; border-radius: 10px;
                background: rgba(255,141,47,.10); color: #ff8d2f;
                display: flex; align-items: center; justify-content: center; font-size: 1.05rem;
            }
            .ti-text { display: flex; flex-direction: column; line-height: 1.25; }
            .ti-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .04em; color: #9aa0a6; }
            .ti-value { font-weight: 600; color: #333; }
            .ticket-attach-btn:hover { background: #ff8d2f; border-color: #ff8d2f; color: #fff; }
            .resolution-note { background: rgba(32, 191, 85, .08); border: 1px solid rgba(32, 191, 85, .2); }

            /* === Conversation === */
            .conversation-card { border-radius: 16px; overflow: hidden; }
            .conv-icon {
                width: 34px; height: 34px; border-radius: 10px;
                background: rgba(255,141,47,.12); color: #ff8d2f;
                display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
            }
            .conv-head-text { line-height: 1; }
            .online-dot {
                width: 8px; height: 8px; border-radius: 50%; background: #20bf55;
                display: inline-block; box-shadow: 0 0 0 3px rgba(32,191,85,.18);
            }
            .chat-box {
                max-height: 520px; overflow-y: auto; padding: 16px 14px;
                background: #f6f7f9; border-radius: 14px;
                background-image: radial-gradient(rgba(0,0,0,.025) 1px, transparent 1px);
                background-size: 16px 16px;
                scrollbar-width: thin; scrollbar-color: #d4d7dc transparent;
            }
            .chat-box::-webkit-scrollbar { width: 7px; }
            .chat-box::-webkit-scrollbar-thumb { background: #d4d7dc; border-radius: 10px; }
            .chat-day { text-align: center; margin: 4px 0 16px; position: relative; }
            .chat-day span {
                display: inline-block; font-size: .72rem; font-weight: 600; color: #8a9099;
                background: #eceef1; padding: .2rem .8rem; border-radius: 999px;
            }
            .chat-avatar {
                width: 38px; height: 38px; min-width: 38px; border-radius: 50%;
                display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff;
            }
            .chat-avatar--admin { background: linear-gradient(135deg, #ff8d2f, #ff6a3d); }
            .chat-avatar--user { background: linear-gradient(135deg, #5b6470, #3b4252); }
            .chat-bubble { max-width: 78%; padding: .8rem 1rem; border-radius: 16px; font-size: .92rem; line-height: 1.5; box-shadow: 0 4px 14px rgba(0,0,0,.06); transition: transform .15s ease, box-shadow .15s ease; }
            .chat-bubble:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.10); }
            .chat-bubble-admin { background: #fff; border: 1px solid #eee; border-top-left-radius: 4px; }
            .chat-bubble-user { background: linear-gradient(135deg, #ff8d2f 0%, #ff6a3d 100%); color: #fff; border-top-right-radius: 4px; }
            .chat-meta { font-size: .74rem; margin-bottom: .3rem; display: flex; align-items: center; gap: .5rem; }
            .chat-bubble-user .chat-meta { color: rgba(255,255,255,.9); }
            .chat-sender { font-weight: 600; display: inline-flex; align-items: center; gap: .35rem; }
            .chat-bubble-user .chat-sender { color: #fff; }
            .chat-dot { width: 7px; height: 7px; border-radius: 50%; background: #ff8d2f; display: inline-block; }
            .chat-bubble-user .chat-dot { background: #fff; }
            .chat-time { opacity: .75; }
            .chat-text { white-space: pre-wrap; word-break: break-word; }
            .chat-attachment { font-size: .82rem; display: inline-block; margin-top: .35rem; text-decoration: none; }
            .chat-file-chip {
                display: inline-flex; align-items: center; gap: .5rem; max-width: 240px;
                margin-top: .5rem; padding: .45rem .6rem; border-radius: 10px; text-decoration: none;
                font-size: .82rem; font-weight: 500;
            }
            .chat-file-chip--admin { background: #fff; border: 1px solid #e6e8ec; color: #ff8d2f; }
            .chat-file-chip--user { background: rgba(255,255,255,.18); color: #fff; border: 1px solid rgba(255,255,255,.25); }
            .chat-file-ic { width: 26px; height: 26px; min-width: 26px; border-radius: 7px; display: flex; align-items: center; justify-content: center; background: rgba(255,141,47,.15); color: #ff8d2f; }
            .chat-file-chip--user .chat-file-ic { background: rgba(255,255,255,.22); color: #fff; }
            .chat-file-name { max-width: 150px; }
            .chat-file-dl { margin-left: auto; opacity: .8; }

            .chat-reply { background: #fafafa; border-radius: 16px; padding: 16px; }
            .chat-input { border-radius: 12px; resize: none; }
            .chat-attach-btn {
                border-radius: 999px; background: #fff; border: 1px solid #dee2e6; color: #555; font-weight: 500;
            }
            .chat-attach-btn:hover { border-color: #ff8d2f; color: #ff8d2f; }

            .resolution-prompt { background: #fffaf2; }
            .rated-badge { color: #ff8d2f; font-weight: 600; }
            .cursor-pointer { cursor: pointer; }
            .spin { animation: ticket-spin 1s linear infinite; }
            @keyframes ticket-spin { to { transform: rotate(360deg); } }
        </style>

    @push('scripts')
        <script>
            $(function () {
                var ticketId = "{{ $ticket->ticket_id }}";
                var $box = $('#chatBox');
                var $count = $('.conversation-count');
                var lastDay = $box.children('.chat-day').last().find('span').text() || null;

                function esc(s) {
                    return $('<div>').text(s == null ? '' : s).html();
                }

                function appendDay(label) {
                    var $last = $box.children('.chat-day').last();
                    if ($last.length && $last.find('span').text() === label) return;
                    $box.append('<div class="chat-day"><span>' + esc(label) + '</span></div>');
                }

                function appendMessage(data) {
                    if ($box.find('[data-msg-id="' + data.id + '"]').length) return;

                    if (data.created_date_label && data.created_date_label !== lastDay) {
                        lastDay = data.created_date_label;
                        appendDay(data.created_date_label);
                    }

                    var isAdmin = data.sender_type === 'admin';
                    var $wrap = $('<div class="d-flex mb-3 ' + (isAdmin ? 'justify-content-start' : 'justify-content-end') + '">');
                    $wrap.attr('data-msg-id', data.id);

                    var html = '';
                    if (isAdmin) {
                        html += '<div class="chat-avatar chat-avatar--admin me-2"><i class="ri-customer-service-2-line"></i></div>';
                    }
                    html += '<div class="chat-bubble ' + (isAdmin ? 'chat-bubble-admin' : 'chat-bubble-user') + '">';
                    html += '<div class="chat-meta">';
                    html += '<span class="chat-sender"><span class="chat-dot"></span>' + (isAdmin ? 'Support Team' : 'You') + '</span>';
                    html += '<span class="chat-time">' + esc(data.created_at) + '</span>';
                    html += '</div>';
                    html += '<div class="chat-text">' + esc(data.message) + '</div>';
                    if (data.attachment) {
                        var icon = data.attachment_is_image ? 'image' : 'file';
                        html += '<a href="' + data.attachment + '" target="_blank" class="chat-file-chip ' + (isAdmin ? 'chat-file-chip--admin' : 'chat-file-chip--user') + '">';
                        html += '<span class="chat-file-ic"><i class="ri-' + icon + '-line"></i></span>';
                        html += '<span class="chat-file-name text-truncate">' + esc(data.attachment_name) + '</span>';
                        html += '<i class="ri-download-line chat-file-dl"></i></a>';
                    }
                    html += '</div>';
                    if (!isAdmin) {
                        html += '<div class="chat-avatar chat-avatar--user ms-2"><i class="ri-user-line"></i></div>';
                    }
                    $wrap.append(html);
                    $box.append($wrap);
                    $box.scrollTop($box[0].scrollHeight);

                    if ($count.length) {
                        var n = parseInt($count.text()) || 0;
                        $count.text((n + 1) + ' messages');
                    }
                }

                // jQuery AJAX reply — no full page reload, message appears instantly
                $('.chat-reply').on('submit', function (e) {
                    e.preventDefault();
                    var $form = $(this);
                    var $btn = $form.find('button[type="submit"]');
                    if ($btn.prop('disabled')) return;

                    $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Sending…');

                    $.ajax({
                        url: $form.attr('action'),
                        method: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function (res) {
                            if (res && res.message) {
                                appendMessage(res.message);
                            }
                            $form[0].reset();
                            $btn.prop('disabled', false).html('<i class="ri-send-plane-line me-1"></i> Send');
                        },
                        error: function () {
                            // Never reload — show what the user typed as a best-effort message
                            var text = $form.find('textarea[name="message"]').val();
                            if (text && text.trim() !== '') {
                                var now = new Date();
                                appendMessage({
                                    id: 'local-' + now.getTime(),
                                    ticket_id: ticketId,
                                    sender_type: 'user',
                                    sender_id: null,
                                    message: text,
                                    attachment: null,
                                    attachment_name: null,
                                    attachment_is_image: false,
                                    created_at: now.toLocaleString('en-IN', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }),
                                    created_date: '',
                                    created_date_label: now.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
                                });
                            }
                            $form[0].reset();
                            $btn.prop('disabled', false).html('<i class="ri-send-plane-line me-1"></i> Send');
                        }
                    });
                });

                // Live incoming messages via Pusher? No — via Pusher Echo (only if loaded)
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
