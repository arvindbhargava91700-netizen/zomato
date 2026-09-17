<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IpSettingController extends Controller
{
    /**
     * Display a listing of the blocked/tracked IPs.
     */
    public function index(): View
    {
        $ips = BlockedIp::latest()->paginate(20);
        return view('manage.admin.ip-settings.index', compact('ips'));
    }

    /**
     * Toggle the block status of an IP address.
     */
    public function toggleBlock(Request $request, $id): RedirectResponse
    {
        $ip = BlockedIp::findOrFail($id);
        
        $ip->is_blocked = !$ip->is_blocked;
        
        if ($ip->is_blocked) {
            $ip->blocked_at = now();
        } else {
            $ip->blocked_at = null;
            $ip->failed_attempts = 0; // Reset attempts when unblocking
        }
        
        $ip->save();

        $status = $ip->is_blocked ? 'blocked' : 'unblocked';
        return back()->with('success', "IP address successfully {$status}.");
    }
}
