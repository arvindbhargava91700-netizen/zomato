<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\ContactMessage;
use App\Models\ContactMessageReply;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $query = ContactMessage::with(['user', 'replies'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => ContactMessage::count(),
            'unread' => ContactMessage::where('status', 'unread')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'closed' => ContactMessage::where('status', 'closed')->count(),
        ];

        return view('manage.admin.contact-messages.index', compact('messages', 'counts'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        if ($contactMessage->status === 'unread') {
            $contactMessage->update(['status' => 'read']);
        }

        $contactMessage->load(['user', 'replies.admin']);

        return view('manage.admin.contact-messages.show', compact('contactMessage'));
    }

    public function update(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:unread,read,replied,closed'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $contactMessage->update($validated);

        return back()->with('success', 'Message status updated successfully.');
    }

    /**
     * Send email reply to the customer and log it in the database.
     */
    public function reply(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'reply_message' => ['required', 'string', 'min:5', 'max:10000'],
        ]);

        $company = CompanySetting::firstSetting();
        $companyName = $company?->company_name ?? Setting::where('key', 'mail_from_name')->value('value') ?? config('app.name', 'Food Express');
        $fromEmail = Setting::where('key', 'mail_from_address')->value('value') ?? config('mail.from.address', 'support@foodexpress.com');

        $recipientEmail = $contactMessage->email;
        $recipientName = $contactMessage->full_name;
        $replySubject = trim($validated['subject']);
        $replyContent = trim($validated['reply_message']);

        // Build reply HTML using dynamic global email header and footer
        $bodyContent = '
            <p style="font-size:16px;margin-top:0;">Dear <strong>' . htmlspecialchars($recipientName) . '</strong>,</p>
            <p>Thank you for reaching out to us. Regarding your inquiry <em>"' . htmlspecialchars($contactMessage->subject ?: 'your message') . '"</em>:</p>
            
            <div style="background:#fff9f4;border-left:4px solid #ff8d2f;padding:16px 20px;margin:20px 0;border-radius:0 8px 8px 0;color:#1e293b;line-height:1.65;font-size:15px;">
                ' . nl2br(htmlspecialchars($replyContent)) . '
            </div>

            <p style="font-size:13.5px;color:#64748b;">If you have any further questions or require more details, feel free to reply directly to this email.</p>

            <div style="background:#f1f5f9;border-radius:8px;padding:12px 16px;margin-top:20px;font-size:13px;color:#64748b;">
                <strong style="color:#334155;">Original Inquiry:</strong><br>
                <em>"' . nl2br(htmlspecialchars($contactMessage->message)) . '"</em>
            </div>

            <p style="margin-top:22px;margin-bottom:0;">Best regards,<br><strong>' . htmlspecialchars(auth('admin')->user()->name ?? 'Admin Support') . '</strong><br><span style="font-size:12px;color:#64748b;">' . htmlspecialchars($companyName) . ' Support Team</span></p>
        ';

        $htmlMessage = \App\Models\EmailTemplate::renderWithLayout($bodyContent, [
            'name' => $recipientName,
            'first_name' => $contactMessage->first_name,
            'email' => $recipientEmail,
            'subject' => $replySubject,
            'admin_name' => auth('admin')->user()->name ?? 'Admin Support',
        ]);

        $deliveryStatus = 'sent';
        $errorMessage = null;

        try {
            Mail::html($htmlMessage, function ($message) use ($recipientEmail, $recipientName, $replySubject, $fromEmail, $companyName) {
                $message->to($recipientEmail, $recipientName)
                        ->subject($replySubject)
                        ->from($fromEmail, $companyName);
            });
        } catch (\Throwable $e) {
            $deliveryStatus = 'failed';
            $errorMessage = $e->getMessage();
        }

        // Log the reply in database
        ContactMessageReply::create([
            'contact_message_id' => $contactMessage->id,
            'admin_id' => Auth::guard('admin')->id(),
            'email_to' => $recipientEmail,
            'subject' => $replySubject,
            'message' => $replyContent,
            'status' => $deliveryStatus,
            'error_message' => $errorMessage,
        ]);

        // Update inquiry status to replied
        $contactMessage->update([
            'status' => 'replied',
        ]);

        if ($deliveryStatus === 'failed') {
            return back()->with('warning', 'Reply was recorded, but email delivery encountered an issue: ' . $errorMessage);
        }

        return back()->with('success', 'Email reply has been sent successfully to ' . $recipientEmail . '!');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Message deleted successfully.');
    }
}
