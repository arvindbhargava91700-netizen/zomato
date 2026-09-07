<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ticket::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->paginate(15)->withQueryString();

        $counts = [
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
            'reopened' => Ticket::where('status', 'reopened')->count(),
        ];

        return view('manage.admin.tickets.index', compact('tickets', 'counts'));
    }

    public function show(string $ticketId): View
    {
        $ticket = Ticket::where('ticket_id', $ticketId)->firstOrFail();
        $messages = $ticket->messages()->orderBy('id')->get();

        TicketNotification::where('ticket_id', $ticket->id)
            ->where('to_type', 'admin')
            ->update(['is_read' => true]);

        if (!$ticket->assigned_to) {
            $ticket->update(['assigned_to' => Auth::guard('admin')->id()]);
        }

        return view('manage.admin.tickets.show', compact('ticket', 'messages'));
    }

    public function reply(Request $request, string $ticketId): RedirectResponse
    {
        $ticket = Ticket::where('ticket_id', $ticketId)->firstOrFail();

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
            'sender_type' => 'admin',
            'sender_id' => Auth::guard('admin')->id(),
            'message' => $data['message'],
            'attachment' => $attachment,
        ]);

        try {
            event(new \App\Events\TicketMessageSent($message));
        } catch (\Throwable $e) {
            report($e);
        }

        if ($ticket->status === 'open') {
            $ticket->markStatus('in_progress');
        }

        $this->notifyUser(
            $ticket,
            "New reply on ticket {$ticket->ticket_id}",
            "Admin replied on your ticket {$ticket->ticket_id}: {$data['message']}"
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

        return redirect()->route('admin.tickets.show', $ticket->ticket_id)
            ->with('success', 'Reply sent to the user.');
    }

    public function updateStatus(Request $request, string $ticketId): RedirectResponse
    {
        $ticket = Ticket::where('ticket_id', $ticketId)->firstOrFail();

        $data = $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,closed,reopened',
            'resolution_note' => 'nullable|string',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
        ]);

        if (!empty($data['priority'])) {
            $ticket->priority = $data['priority'];
        }

        if ($data['status'] === 'resolved') {
            $ticket->markStatus('resolved', $data['resolution_note'] ?? null);
            $this->notifyUser(
                $ticket,
                "Ticket {$ticket->ticket_id} resolved",
                "Your ticket {$ticket->ticket_id} has been resolved. " . ($data['resolution_note'] ?? '')
            );
        } elseif ($data['status'] === 'closed') {
            $ticket->markStatus('closed', $data['resolution_note'] ?? null);
            $this->notifyUser(
                $ticket,
                "Ticket {$ticket->ticket_id} closed",
                "Your ticket {$ticket->ticket_id} has been closed."
            );
        } else {
            $ticket->markStatus($data['status']);
            $this->notifyUser(
                $ticket,
                "Ticket {$ticket->ticket_id} updated",
                "Your ticket {$ticket->ticket_id} status is now: " . ucfirst($data['status']) . "."
            );
        }

        return redirect()->route('admin.tickets.show', $ticket->ticket_id)
            ->with('success', 'Ticket status updated.');
    }

    protected function notifyUser(Ticket $ticket, string $title, string $message): void
    {
        $email = User::find($ticket->user_id)?->email ?? null;

        TicketNotification::notify($ticket, $ticket->user_type, $ticket->user_id, $title, $message, $email);
    }
}
