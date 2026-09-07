<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    protected string $guard = 'web';
    protected string $userType = 'customer';
    protected string $viewPrefix = 'manage.front.tickets';
    protected string $routePrefix = 'tickets-';

    protected function user()
    {
        return Auth::guard($this->guard)->user();
    }

    protected function userEmail(): ?string
    {
        return $this->user()->email ?? null;
    }

    protected function restaurantId(): ?int
    {
        return null;
    }

    public function help(): View
    {
        return view($this->viewPrefix . '.help');
    }

    public function index(): View
    {
        $tickets = Ticket::where('user_type', $this->userType)
            ->where('user_id', $this->user()->id)
            ->latest()
            ->paginate(10);

        return view($this->viewPrefix . '.index', compact('tickets'));
    }

    public function create(): View
    {
        return view($this->viewPrefix . '.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category' => 'required|string|in:order,payment,delivery,account,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'order_id' => 'nullable|string|max:50',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $attachment = null;
        if ($request->hasFile('attachment')) {
            $attachment = Storage::disk('public')->putFile('tickets', $request->file('attachment'));
        }

        $num = (int) Ticket::max('id') + 1;
        $ticketId = 'TCK' . str_pad($num, 4, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'ticket_id' => $ticketId,
            'user_type' => $this->userType,
            'user_id' => $this->user()->id,
            'restaurant_id' => $this->restaurantId(),
            'order_id' => $data['order_id'] ?? null,
            'category' => $data['category'],
            'subject' => $data['subject'],
            'description' => $data['description'],
            'attachment' => $attachment,
            'priority' => $data['priority'],
            'status' => 'open',
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => $this->user()->id,
            'message' => $data['description'],
            'attachment' => $attachment,
        ]);

        try {
            event(new \App\Events\TicketMessageSent($message));
        } catch (\Throwable $e) {
            report($e);
        }

        TicketNotification::notify(
            $ticket, 'admin', 0,
            "New Support Ticket {$ticketId}",
            "A new ticket was raised by a {$this->userType}: {$data['subject']}",
            $this->adminEmail()
        );

        return redirect()->route($this->routePrefix . 'show', $ticket->ticket_id)
            ->with('success', "Ticket {$ticketId} created successfully. Our team will respond shortly.");
    }

    public function show(string $ticketId): View
    {
        $ticket = $this->ownedTicket($ticketId);

        $messages = $ticket->messages()->with(['ticket'])->orderBy('id')->get();

        $unread = TicketNotification::where('ticket_id', $ticket->id)
            ->where('to_type', $this->userType)
            ->where('is_read', false)->get();
        foreach ($unread as $n) {
            $n->update(['is_read' => true]);
        }

        return view($this->viewPrefix . '.show', compact('ticket', 'messages'));
    }

    public function reply(Request $request, string $ticketId): RedirectResponse
    {
        $ticket = $this->ownedTicket($ticketId);

        $data = $request->validate([
            'message' => 'required|string|min:2',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $attachment = null;
        if ($request->hasFile('attachment')) {
            $attachment = Storage::disk('public')->putFile('tickets', $request->file('attachment'));
        }

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => $this->user()->id,
            'message' => $data['message'],
            'attachment' => $attachment,
        ]);

        try {
            event(new \App\Events\TicketMessageSent($message));
        } catch (\Throwable $e) {
            report($e);
        }

        if (in_array($ticket->status, ['resolved', 'closed'])) {
            $ticket->markStatus('reopened');
        }

        TicketNotification::notify(
            $ticket, 'admin', 0,
            "New reply on ticket {$ticket->ticket_id}",
            "Customer replied on ticket {$ticket->ticket_id}: {$data['message']}",
            $this->adminEmail()
        );

        if ($request->expectsJson() || $request->ajax()) {
            $ext = $attachment ? strtolower(pathinfo($attachment, PATHINFO_EXTENSION)) : null;

            return response()->json([
                'ok' => true,
                'message' => [
                    'id' => $message->id,
                    'ticket_id' => $ticket->ticket_id,
                    'sender_type' => $message->sender_type,
                    'sender_id' => $message->sender_id,
                    'message' => $message->message,
                    'attachment' => $attachment ? asset('storage/' . $attachment) : null,
                    'attachment_name' => $attachment ? basename($attachment) : null,
                    'attachment_is_image' => in_array($ext, ['jpg', 'jpeg', 'png']),
                    'created_at' => $message->created_at->format('d M, h:i A'),
                    'created_date' => $message->created_at->format('Y-m-d'),
                    'created_date_label' => $message->created_at->format('d M Y'),
                ],
            ]);
        }

        return redirect()->route($this->routePrefix . 'show', $ticket->ticket_id)
            ->with('success', 'Your reply has been sent.');
    }

    public function close(Request $request, string $ticketId): RedirectResponse
    {
        $ticket = $this->ownedTicket($ticketId);
        $ticket->markStatus('closed');

        TicketNotification::notify(
            $ticket, 'admin', 0,
            "Ticket {$ticket->ticket_id} closed",
            "User marked ticket {$ticket->ticket_id} as resolved/satisfied.",
            $this->adminEmail()
        );

        return redirect()->route($this->routePrefix . 'show', $ticket->ticket_id)
            ->with('success', 'Ticket closed. Thank you!');
    }

    public function reopen(Request $request, string $ticketId): RedirectResponse
    {
        $ticket = $this->ownedTicket($ticketId);
        $ticket->markStatus('reopened');

        TicketNotification::notify(
            $ticket, 'admin', 0,
            "Ticket {$ticket->ticket_id} reopened",
            "User reopened ticket {$ticket->ticket_id} as not resolved.",
            $this->adminEmail()
        );

        return redirect()->route($this->routePrefix . 'show', $ticket->ticket_id)
            ->with('success', 'Ticket reopened. Our team will look into it again.');
    }

    public function rate(Request $request, string $ticketId): RedirectResponse
    {
        $ticket = $this->ownedTicket($ticketId);

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $ticket->update(['rating' => $data['rating']]);

        return redirect()->route($this->routePrefix . 'show', $ticket->ticket_id)
            ->with('success', 'Thanks for rating our support!');
    }

    protected function ownedTicket(string $ticketId): Ticket
    {
        $ticket = Ticket::where('ticket_id', $ticketId)
            ->where('user_type', $this->userType)
            ->where('user_id', $this->user()->id)
            ->firstOrFail();

        return $ticket;
    }

    protected function adminEmail(): ?string
    {
        $admin = \App\Models\Admin::where('role', 'admin')->first();
        return $admin?->email;
    }
}
