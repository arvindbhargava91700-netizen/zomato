<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Cuisine;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use App\Models\RestaurantBlog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Get owner's assigned restaurant model or abort.
     */
    private function getAssignedRestaurant(): Restaurant
    {
        $owner = auth()->user();
        $restaurant = Restaurant::where('user_id', $owner?->id)->first();

        if (!$restaurant) {
            abort(403, 'No restaurant assigned to your account. Please contact administrator.');
        }

        return $restaurant;
    }

    /**
     * Display a listing of the restaurant's blogs.
     */
    public function index(Request $request): View
    {
        $restaurant = $this->getAssignedRestaurant();

        $query = RestaurantBlog::with(['category', 'cuisine'])->where('restaurant_id', $restaurant->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $blogs = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => RestaurantBlog::where('restaurant_id', $restaurant->id)->count(),
            'published' => RestaurantBlog::where('restaurant_id', $restaurant->id)->where('status', RestaurantBlog::STATUS_PUBLISHED)->count(),
            'draft' => RestaurantBlog::where('restaurant_id', $restaurant->id)->where('status', RestaurantBlog::STATUS_DRAFT)->count(),
            'pending' => RestaurantBlog::where('restaurant_id', $restaurant->id)->where('approval_status', RestaurantBlog::APPROVAL_PENDING)->count(),
            'approved' => RestaurantBlog::where('restaurant_id', $restaurant->id)->where('approval_status', RestaurantBlog::APPROVAL_APPROVED)->count(),
            'rejected' => RestaurantBlog::where('restaurant_id', $restaurant->id)->where('approval_status', RestaurantBlog::APPROVAL_REJECTED)->count(),
        ];

        return view('manage.restaurant.blogs.index', compact('blogs', 'counts', 'restaurant'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create(): View
    {
        $restaurant = $this->getAssignedRestaurant();
        $categories = FoodCategory::where('status', 'active')->orderBy('name')->get();
        $cuisines = Cuisine::where('status', 'active')->orderBy('name')->get();
        return view('manage.restaurant.blogs.create', compact('restaurant', 'categories', 'cuisines'));
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'food_category_id' => ['nullable', 'exists:food_categories,id'],
            'cuisine_id' => ['nullable', 'exists:cuisines,id'],
            'slug' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:600'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'status' => ['required', 'in:draft,published,inactive'],
        ]);

        $slug = !empty($validated['slug'])
            ? RestaurantBlog::generateUniqueSlug($validated['slug'])
            : RestaurantBlog::generateUniqueSlug($validated['title']);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $fileName = 'blog_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/restaurant/blogs');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $imagePath = 'uploads/restaurant/blogs/' . $fileName;
        }

        $blog = RestaurantBlog::create([
            'restaurant_id' => $restaurant->id,
            'food_category_id' => $validated['food_category_id'] ?? null,
            'cuisine_id' => $validated['cuisine_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'short_description' => $validated['short_description'] ?? null,
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'status' => $validated['status'],
            'approval_status' => RestaurantBlog::APPROVAL_PENDING,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        AuditLog::log(
            'Restaurant Blog',
            'created',
            null,
            ['id' => $blog->id, 'title' => $blog->title, 'status' => $blog->status],
            "Blog '{$blog->title}' created and submitted for admin review."
        );

        $statusMsg = $blog->status === 'published'
            ? "Blog '{$blog->title}' created and submitted to Admin for review! Once approved, it will be live on the website."
            : "Blog '{$blog->title}' saved as draft successfully!";

        return redirect()->route('restaurant.blogs.index')->with('success', $statusMsg);
    }

    /**
     * Display the specified blog.
     */
    public function show(RestaurantBlog $blog): View
    {
        $restaurant = $this->getAssignedRestaurant();
        if ($blog->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $blog->load(['author', 'approver', 'category', 'cuisine']);
        return view('manage.restaurant.blogs.show', compact('blog', 'restaurant'));
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit(RestaurantBlog $blog): View
    {
        $restaurant = $this->getAssignedRestaurant();
        if ($blog->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $categories = FoodCategory::where('status', 'active')->orderBy('name')->get();
        $cuisines = Cuisine::where('status', 'active')->orderBy('name')->get();
        return view('manage.restaurant.blogs.edit', compact('blog', 'restaurant', 'categories', 'cuisines'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, RestaurantBlog $blog): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();
        if ($blog->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'food_category_id' => ['nullable', 'exists:food_categories,id'],
            'cuisine_id' => ['nullable', 'exists:cuisines,id'],
            'slug' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:600'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'status' => ['required', 'in:draft,published,inactive'],
        ]);

        $slug = !empty($validated['slug'])
            ? RestaurantBlog::generateUniqueSlug($validated['slug'], $blog->id)
            : RestaurantBlog::generateUniqueSlug($validated['title'], $blog->id);

        $imagePath = $blog->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {
                File::delete(public_path($blog->featured_image));
            }

            $file = $request->file('featured_image');
            $fileName = 'blog_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/restaurant/blogs');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $imagePath = 'uploads/restaurant/blogs/' . $fileName;
        }

        // If blog was rejected or if significant changes are made when publishing, reset to pending
        $approvalStatus = $blog->approval_status;
        if ($blog->approval_status === RestaurantBlog::APPROVAL_REJECTED) {
            $approvalStatus = RestaurantBlog::APPROVAL_PENDING;
        }

        $blog->update([
            'food_category_id' => $validated['food_category_id'] ?? null,
            'cuisine_id' => $validated['cuisine_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'short_description' => $validated['short_description'] ?? null,
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'status' => $validated['status'],
            'approval_status' => $approvalStatus,
            'updated_by' => auth()->id(),
        ]);


        AuditLog::log(
            'Restaurant Blog',
            'updated',
            null,
            ['id' => $blog->id, 'title' => $blog->title, 'status' => $blog->status],
            "Blog '{$blog->title}' updated."
        );

        return redirect()->route('restaurant.blogs.index')
            ->with('success', "Blog '{$blog->title}' updated successfully!");
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy(RestaurantBlog $blog): RedirectResponse
    {
        $restaurant = $this->getAssignedRestaurant();
        if ($blog->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $title = $blog->title;
        $blog->delete();

        AuditLog::log(
            'Restaurant Blog',
            'deleted',
            null,
            ['id' => $blog->id, 'title' => $title],
            "Blog '{$title}' deleted."
        );

        return redirect()->route('restaurant.blogs.index')
            ->with('success', "Blog '{$title}' deleted successfully.");
    }

    /**
     * Toggle status (Published / Draft).
     */
    public function toggleStatus(RestaurantBlog $blog): RedirectResponse|JsonResponse
    {
        $restaurant = $this->getAssignedRestaurant();
        if ($blog->restaurant_id !== $restaurant->id) {
            abort(403);
        }

        $newStatus = ($blog->status === RestaurantBlog::STATUS_PUBLISHED)
            ? RestaurantBlog::STATUS_DRAFT
            : RestaurantBlog::STATUS_PUBLISHED;

        $blog->status = $newStatus;
        $blog->save();

        $message = "Blog '{$blog->title}' status set to " . ucfirst($newStatus) . '.';

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $blog->status,
                'is_live' => $blog->isLive(),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
