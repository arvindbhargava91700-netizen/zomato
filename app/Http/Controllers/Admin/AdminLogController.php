<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLogController extends Controller
{
    /**
     * Display a listing of admin logs.
     */
    public function index(Request $request): View
    {
        $query = AdminLog::with('admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('logged_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59',
            ]);
        } elseif ($request->filled('date_from')) {
            $query->whereDate('logged_at', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->whereDate('logged_at', '<=', $request->date_to);
        }

        $logs = $query->latest('logged_at')->paginate(20)->withQueryString();

        $actions = AdminLog::distinct()->orderBy('action')->pluck('action');

        return view('manage.admin.logs.index', compact('logs', 'actions'));
    }
}