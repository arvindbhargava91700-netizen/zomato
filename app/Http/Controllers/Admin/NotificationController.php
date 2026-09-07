<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the logged in admin.
     */
    public function index(): View
    {
        $notifications = Auth::guard('admin')->user()->notifications()->paginate(20);

        return view('manage.admin.notifications.index', compact('notifications'));
    }

    /**
     * Mark all notifications as read and redirect to the settlement list.
     */
    public function markAllAsRead(): RedirectResponse
    {
        Auth::guard('admin')->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark a single notification as read and follow its link.
     */
    public function show(string $id): RedirectResponse
    {
        $notification = Auth::guard('admin')->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = data_get($notification->data, 'url', route('admin.dashboard'));

        return redirect()->to($url);
    }
}