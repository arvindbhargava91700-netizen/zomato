<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\RestaurantBlog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantBlogController extends Controller
{
    /**
     * Display listing of all restaurant blogs for Admin review.
     */
    public function index(Request $request): View
    {
        $query = RestaurantBlog::with(['restaurant', 'author', 'category', 'cuisine']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhereHas('restaurant', function ($q2) use ($search) {
                      $q2->where('restaurant_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $blogs = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => RestaurantBlog::count(),
            'pending' => RestaurantBlog::where('approval_status', RestaurantBlog::APPROVAL_PENDING)->count(),
            'approved' => RestaurantBlog::where('approval_status', RestaurantBlog::APPROVAL_APPROVED)->count(),
            'rejected' => RestaurantBlog::where('approval_status', RestaurantBlog::APPROVAL_REJECTED)->count(),
            'published' => RestaurantBlog::where('status', RestaurantBlog::STATUS_PUBLISHED)->count(),
        ];

        return view('manage.admin.restaurant-blogs.index', compact('blogs', 'counts'));
    }

    /**
     * Display detailed blog for review and approval.
     */
    public function show(RestaurantBlog $restaurant_blog): View
    {
        $restaurant_blog->load(['restaurant', 'author', 'approver', 'category', 'cuisine']);
        return view('manage.admin.restaurant-blogs.show', compact('restaurant_blog'));
    }

    /**
     * Approve restaurant blog.
     */
    public function approve(Request $request, RestaurantBlog $restaurant_blog): RedirectResponse
    {
        $restaurant_blog->update([
            'approval_status' => RestaurantBlog::APPROVAL_APPROVED,
            'approved_at' => now(),
            'approved_by' => auth()->guard('admin')->id(),
            'admin_remarks' => $request->input('admin_remarks') ?: null,
        ]);

        AuditLog::log(
            'Restaurant Blog',
            'approved',
            null,
            ['id' => $restaurant_blog->id, 'title' => $restaurant_blog->title, 'restaurant_id' => $restaurant_blog->restaurant_id],
            "Restaurant blog '{$restaurant_blog->title}' approved by admin."
        );

        return redirect()->route('admin.restaurant-blogs.show', $restaurant_blog->id)
            ->with('success', "Blog '{$restaurant_blog->title}' has been APPROVED and is now live for customers!");
    }

    /**
     * Reject restaurant blog with admin remarks.
     */
    public function reject(Request $request, RestaurantBlog $restaurant_blog): RedirectResponse
    {
        $data = $request->validate([
            'admin_remarks' => ['required', 'string', 'max:1000'],
        ]);

        $restaurant_blog->update([
            'approval_status' => RestaurantBlog::APPROVAL_REJECTED,
            'approved_at' => now(),
            'approved_by' => auth()->guard('admin')->id(),
            'admin_remarks' => $data['admin_remarks'],
        ]);

        AuditLog::log(
            'Restaurant Blog',
            'rejected',
            null,
            ['id' => $restaurant_blog->id, 'title' => $restaurant_blog->title, 'reason' => $data['admin_remarks']],
            "Restaurant blog '{$restaurant_blog->title}' rejected with reason: {$data['admin_remarks']}"
        );

        return redirect()->route('admin.restaurant-blogs.show', $restaurant_blog->id)
            ->with('success', "Blog '{$restaurant_blog->title}' has been REJECTED. Rejection reason was saved.");
    }
}
