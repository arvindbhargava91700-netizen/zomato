<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    public function index(Request $request): View
    {
        $query = EmailTemplate::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $templates = $query->latest()->paginate(15)->withQueryString();
        $categories = EmailTemplate::distinct()->whereNotNull('category')->orderBy('category')->pluck('category');

        $emailHeaderHtml = Setting::get('email_header_html') ?: EmailTemplate::defaultHeader();
        $emailFooterHtml = Setting::get('email_footer_html') ?: EmailTemplate::defaultFooter();

        return view('manage.admin.email-templates.index', compact('templates', 'categories', 'emailHeaderHtml', 'emailFooterHtml'));
    }

    /**
     * Save the global email header and footer layout.
     */
    public function saveLayout(Request $request): RedirectResponse
    {
        $request->validate([
            'email_header_html' => ['nullable', 'string'],
            'email_footer_html' => ['nullable', 'string'],
        ]);

        Setting::set('email_header_html', $request->input('email_header_html'));
        Setting::set('email_footer_html', $request->input('email_footer_html'));

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email header & footer saved successfully.');
    }

    public function create(): View
    {
        return view('manage.admin.email-templates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'unique:email_templates,slug'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:60'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $data['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $data['slug']));
        $data['status'] = $data['status'] ?? 'active';

        EmailTemplate::create($data);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function edit(EmailTemplate $emailTemplate): View
    {
        return view('manage.admin.email-templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:120', 'unique:email_templates,slug,' . $emailTemplate->id],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:60'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $data['slug'] = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $data['slug']));
        $data['status'] = $data['status'] ?? 'active';

        $emailTemplate->update($data);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate): RedirectResponse
    {
        $emailTemplate->delete();
        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template deleted successfully.');
    }

    public function toggleStatus(EmailTemplate $emailTemplate): RedirectResponse
    {
        $emailTemplate->update([
            'status' => $emailTemplate->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', "Email template status updated to {$emailTemplate->status}.");
    }
}
