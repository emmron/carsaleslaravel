<?php

namespace App\Http\Controllers;

use App\Models\CarListing;
use App\Models\Feature;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Admin dashboard view.
     */
    public function dashboard()
    {
        // Only allow admins
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $stats = [
            'totalUsers' => User::count(),
            'totalListings' => CarListing::count(),
            'availableListings' => CarListing::available()->count(),
            'soldListings' => CarListing::where('status', 'sold')->count(),
            'totalInquiries' => Inquiry::count(),
            'newInquiries' => Inquiry::where('status', 'new')->count(),
        ];

        $recentListings = CarListing::with(['user', 'images'])
            ->latest()
            ->limit(5)
            ->get();

        $recentInquiries = Inquiry::with(['carListing', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentListings' => $recentListings,
            'recentInquiries' => $recentInquiries,
            'recentUsers' => $recentUsers,
        ]);
    }

    /**
     * Display a listing of all users.
     */
    public function users()
    {
        // Only allow admins
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::withCount('listings')
            ->latest()
            ->paginate(20);

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    /**
     * Display a listing of all car listings.
     */
    public function listings()
    {
        // Only allow admins
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $listings = CarListing::with(['user', 'images'])
            ->latest()
            ->paginate(20);

        return view('admin.listings', [
            'listings' => $listings,
        ]);
    }

    /**
     * Toggle the featured status of a car listing.
     */
    public function toggleFeatured(CarListing $listing)
    {
        // Only allow admins
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $listing->featured = !$listing->featured;
        $listing->save();

        return redirect()->back()
            ->with('success', 'Featured status updated successfully!');
    }

    /**
     * Display a listing of all features.
     */
    public function features()
    {
        // Only allow admins
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $features = Feature::withCount('carListings')
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.features', [
            'features' => $features,
        ]);
    }

    /**
     * Store a newly created feature in storage.
     */
    public function storeFeature(Request $request)
    {
        // Only allow admins
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        Feature::create($validated);

        return redirect()->route('admin.features')
            ->with('success', 'Feature created successfully!');
    }

    /**
     * Update the specified feature in storage.
     */
    public function updateFeature(Request $request, Feature $feature)
    {
        // Only allow admins
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        $feature->update($validated);

        return redirect()->route('admin.features')
            ->with('success', 'Feature updated successfully!');
    }

    /**
     * Remove the specified feature from storage.
     */
    public function destroyFeature(Feature $feature)
    {
        // Only allow admins
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $feature->delete();

        return redirect()->route('admin.features')
            ->with('success', 'Feature deleted successfully!');
    }
}