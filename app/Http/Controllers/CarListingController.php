<?php

namespace App\Http\Controllers;

use App\Models\CarListing;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CarListingController extends Controller
{
    /**
     * Display a listing of the car listings.
     */
    public function index(Request $request)
    {
        $query = CarListing::query()->with(['images', 'user'])->available();

        // Search by keyword
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Filter by make
        if ($request->has('make')) {
            $query->where('make', $request->input('make'));
        }

        // Filter by model
        if ($request->has('model')) {
            $query->where('model', $request->input('model'));
        }

        // Filter by body type
        if ($request->has('body_type')) {
            $query->where('body_type', $request->input('body_type'));
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Filter by year range
        if ($request->has('min_year')) {
            $query->where('year', '>=', $request->input('min_year'));
        }

        if ($request->has('max_year')) {
            $query->where('year', '<=', $request->input('max_year'));
        }

        // Sort results
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        $listings = $query->paginate(12);

        return view('listings.index', [
            'listings' => $listings,
            'makes' => CarListing::select('make')->distinct()->pluck('make'),
            'bodyTypes' => CarListing::select('body_type')->distinct()->pluck('body_type'),
        ]);
    }

    /**
     * Show the form for creating a new car listing.
     */
    public function create()
    {
        $features = Feature::orderBy('category')->orderBy('name')->get();

        return view('listings.create', [
            'features' => $features,
        ]);
    }

    /**
     * Store a newly created car listing in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'trim' => 'nullable|string|max:255',
            'body_type' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'odometer' => 'required|integer|min:0',
            'color' => 'required|string|max:255',
            'vin' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'registration_expiry' => 'nullable|date',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        $listing = new CarListing($validated);
        $listing->user_id = Auth::id();
        $listing->save();

        // Handle features
        if ($request->has('features')) {
            $listing->features()->attach($request->input('features'));
        }

        // Handle images
        if ($request->hasFile('images')) {
            $isPrimary = true;
            $order = 0;

            foreach ($request->file('images') as $image) {
                $path = $image->store('car-images', 'public');

                $listing->images()->create([
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'order' => $order,
                ]);

                $isPrimary = false;
                $order++;
            }
        }

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Your car listing has been created successfully!');
    }

    /**
     * Display the specified car listing.
     */
    public function show(CarListing $listing)
    {
        $listing->load(['images', 'features', 'user']);

        // Get similar listings
        $similarListings = CarListing::where('id', '!=', $listing->id)
            ->where(function ($query) use ($listing) {
                $query->where('make', $listing->make)
                    ->orWhere('model', $listing->model);
            })
            ->available()
            ->with('images')
            ->limit(4)
            ->get();

        return view('listings.show', [
            'listing' => $listing,
            'similarListings' => $similarListings,
        ]);
    }

    /**
     * Show the form for editing the specified car listing.
     */
    public function edit(CarListing $listing)
    {
        // Check if the current user is the owner or an admin
        if (Auth::id() !== $listing->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $features = Feature::orderBy('category')->orderBy('name')->get();
        $selectedFeatures = $listing->features->pluck('id')->toArray();

        return view('listings.edit', [
            'listing' => $listing,
            'features' => $features,
            'selectedFeatures' => $selectedFeatures,
        ]);
    }

    /**
     * Update the specified car listing in storage.
     */
    public function update(Request $request, CarListing $listing)
    {
        // Check if the current user is the owner or an admin
        if (Auth::id() !== $listing->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'trim' => 'nullable|string|max:255',
            'body_type' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'odometer' => 'required|integer|min:0',
            'color' => 'required|string|max:255',
            'vin' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'registration_expiry' => 'nullable|date',
            'status' => 'required|in:available,sold,pending',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        $listing->update($validated);

        // Handle features
        if ($request->has('features')) {
            $listing->features()->sync($request->input('features'));
        } else {
            $listing->features()->detach();
        }

        // Handle new images
        if ($request->hasFile('new_images')) {
            $order = $listing->images->max('order') + 1 ?? 0;

            foreach ($request->file('new_images') as $image) {
                $path = $image->store('car-images', 'public');

                $listing->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                    'order' => $order,
                ]);

                $order++;
            }
        }

        // Handle image deletion
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $imageId) {
                $image = $listing->images()->find($imageId);

                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            // If the primary image was deleted, set a new primary image
            if (!$listing->images()->where('is_primary', true)->exists() && $listing->images()->count() > 0) {
                $listing->images()->first()->update(['is_primary' => true]);
            }
        }

        // Handle primary image
        if ($request->has('primary_image')) {
            $listing->images()->update(['is_primary' => false]);
            $listing->images()->where('id', $request->input('primary_image'))->update(['is_primary' => true]);
        }

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Your car listing has been updated successfully!');
    }

    /**
     * Remove the specified car listing from storage.
     */
    public function destroy(CarListing $listing)
    {
        // Check if the current user is the owner or an admin
        if (Auth::id() !== $listing->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete all images from storage
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $listing->delete();

        return redirect()->route('listings.index')
            ->with('success', 'Your car listing has been deleted successfully!');
    }

    /**
     * Display a listing of the user's car listings.
     */
    public function myListings()
    {
        $listings = CarListing::where('user_id', Auth::id())
            ->with('images')
            ->latest()
            ->paginate(10);

        return view('listings.my-listings', [
            'listings' => $listings,
        ]);
    }
}