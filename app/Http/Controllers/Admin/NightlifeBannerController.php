<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNightlifeBannerRequest;
use App\Http\Requests\Admin\UpdateNightlifeBannerRequest;
use App\Models\NightlifeBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NightlifeBannerController extends Controller
{
    /**
     * Display a listing of nightlife banners.
     */
    public function index(Request $request): View
    {
        $query = NightlifeBanner::query();

        if ($request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $nightlifeBanners = $query->latest()->paginate(10)->withQueryString();

        return view('manage.admin.nightlife-banners.index', compact('nightlifeBanners'));
    }

    /**
     * Show the form for creating a new nightlife banner.
     */
    public function create(): View
    {
        return view('manage.admin.nightlife-banners.create');
    }

    /**
     * Store a newly created nightlife banner in storage.
     */
    public function store(StoreNightlifeBannerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);

        // Upload Banner image
        if ($request->hasFile('banner')) {
            $bannerName = time() . '_banner_' . Str::random(6) . '.' . $request->file('banner')->extension();
            $request->file('banner')->move(public_path('uploads/nightlife/banners'), $bannerName);
            $data['banner'] = 'uploads/nightlife/banners/' . $bannerName;
        }

        $data['created_by'] = Auth::guard('admin')->id();

        NightlifeBanner::create($data);

        return redirect()->route('admin.nightlife-banners.index')->with('success', 'Nightlife banner created successfully.');
    }

    /**
     * Display the specified nightlife banner details.
     */
    public function show(NightlifeBanner $nightlifeBanner): View
    {
        $nightlifeBanner->load('creator', 'updater');
        return view('manage.admin.nightlife-banners.show', compact('nightlifeBanner'));
    }

    /**
     * Show the form for editing the specified nightlife banner.
     */
    public function edit(NightlifeBanner $nightlifeBanner): View
    {
        return view('manage.admin.nightlife-banners.edit', compact('nightlifeBanner'));
    }

    /**
     * Update the specified nightlife banner in storage.
     */
    public function update(UpdateNightlifeBannerRequest $request, NightlifeBanner $nightlifeBanner): RedirectResponse
    {
        $data = $request->validated();

        // Slug handling
        if (!empty($data['slug']) && $data['slug'] !== $nightlifeBanner->slug) {
            $data['slug'] = Str::slug($data['slug']);
        } elseif (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Handle Banner update
        if ($request->hasFile('banner')) {
            if ($nightlifeBanner->banner && File::exists(public_path($nightlifeBanner->banner))) {
                File::delete(public_path($nightlifeBanner->banner));
            }

            $bannerName = time() . '_banner_' . Str::random(6) . '.' . $request->file('banner')->extension();
            $request->file('banner')->move(public_path('uploads/nightlife/banners'), $bannerName);
            $data['banner'] = 'uploads/nightlife/banners/' . $bannerName;
        }

        $data['updated_by'] = Auth::guard('admin')->id();

        $nightlifeBanner->update($data);

        return redirect()->route('admin.nightlife-banners.index')->with('success', 'Nightlife banner updated successfully.');
    }

    /**
     * Remove the specified nightlife banner from storage (Soft Delete).
     */
    public function destroy(NightlifeBanner $nightlifeBanner): RedirectResponse
    {
        $nightlifeBanner->delete();

        return redirect()->route('admin.nightlife-banners.index')->with('success', 'Nightlife banner moved to trash successfully.');
    }

    /**
     * Restore a soft-deleted nightlife banner.
     */
    public function restore($id): RedirectResponse
    {
        $nightlifeBanner = NightlifeBanner::onlyTrashed()->findOrFail($id);
        $nightlifeBanner->restore();

        return redirect()->route('admin.nightlife-banners.index', ['trashed' => 1])->with('success', 'Nightlife banner restored successfully.');
    }

    /**
     * Permanently delete a nightlife banner from storage.
     */
    public function forceDelete($id): RedirectResponse
    {
        $nightlifeBanner = NightlifeBanner::onlyTrashed()->findOrFail($id);

        // Delete associated banner file
        if ($nightlifeBanner->banner && File::exists(public_path($nightlifeBanner->banner))) {
            File::delete(public_path($nightlifeBanner->banner));
        }

        $nightlifeBanner->forceDelete();

        return redirect()->route('admin.nightlife-banners.index', ['trashed' => 1])->with('success', 'Nightlife banner permanently deleted.');
    }

    /**
     * Toggle the active/inactive status of a nightlife banner.
     */
    public function toggleStatus(NightlifeBanner $nightlifeBanner): RedirectResponse
    {
        $newStatus = $nightlifeBanner->status === 'active' ? 'inactive' : 'active';
        $nightlifeBanner->update([
            'status' => $newStatus,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Nightlife banner status changed to {$newStatus}.");
    }
}