<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the logged in delivery partner.
     */
    public function index(): View
    {
        $notifications = Auth::user()->notifications()->paginate(20);

        return view('manage.delivery-partner.notifications.index', compact('notifications'));
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark a single notification as read (from the header dropdown).
     */
    public function markRead(string $id): RedirectResponse
    {
        Auth::user()->notifications()->findOrFail($id)->markAsRead();

        return back();
    }

    /**
     * Mark a single notification as read and follow its link.
     */
    public function show(string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = data_get($notification->data, 'url', route('delivery-partner.dashboard'));

        return redirect()->to($url);
    }
}