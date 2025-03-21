<?php

namespace App\Http\Controllers;

use App\Models\CarListing;
use App\Models\Inquiry;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    /**
     * Store a newly created inquiry in storage.
     */
    public function store(Request $request, CarListing $listing)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'name' => 'required_without:user_id|string|max:255',
            'email' => 'required_without:user_id|email|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $inquiry = new Inquiry($validated);
        $inquiry->car_listing_id = $listing->id;

        if (Auth::check()) {
            $inquiry->user_id = Auth::id();
        }

        $inquiry->save();

        // Create the first message
        $message = new Message([
            'message' => $inquiry->message,
            'user_id' => Auth::id(),
        ]);

        $inquiry->messages()->save($message);

        return redirect()->back()
            ->with('success', 'Your inquiry has been sent successfully!');
    }

    /**
     * Display a listing of inquiries for the authenticated user.
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            // Admin sees all inquiries
            $inquiries = Inquiry::with(['carListing', 'carListing.images'])
                ->latest()
                ->paginate(15);
        } else {
            // Regular user sees inquiries for their listings and inquiries they've made
            $inquiries = Inquiry::where(function ($query) {
                $query->whereHas('carListing', function ($q) {
                    $q->where('user_id', Auth::id());
                })->orWhere('user_id', Auth::id());
            })
            ->with(['carListing', 'carListing.images'])
            ->latest()
            ->paginate(15);
        }

        return view('inquiries.index', [
            'inquiries' => $inquiries,
        ]);
    }

    /**
     * Display the specified inquiry.
     */
    public function show(Inquiry $inquiry)
    {
        // Check if the current user is the owner of the listing, the inquiry creator, or an admin
        if (Auth::id() !== $inquiry->carListing->user_id &&
            Auth::id() !== $inquiry->user_id &&
            !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $inquiry->load(['carListing', 'carListing.images', 'messages.user']);

        // Mark as read if the user is the listing owner
        if (Auth::id() === $inquiry->carListing->user_id && $inquiry->status === 'new') {
            $inquiry->status = 'read';
            $inquiry->save();
        }

        // Mark all messages as read for the current user
        $inquiry->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('inquiries.show', [
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Store a reply to an inquiry.
     */
    public function reply(Request $request, Inquiry $inquiry)
    {
        // Check if the current user is the owner of the listing, the inquiry creator, or an admin
        if (Auth::id() !== $inquiry->carListing->user_id &&
            Auth::id() !== $inquiry->user_id &&
            !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $message = new Message([
            'message' => $validated['message'],
            'user_id' => Auth::id(),
            'is_read' => false,
        ]);

        $inquiry->messages()->save($message);

        // Update the inquiry status if the seller is replying
        if (Auth::id() === $inquiry->carListing->user_id) {
            $inquiry->status = 'replied';
            $inquiry->save();
        }

        return redirect()->route('inquiries.show', $inquiry)
            ->with('success', 'Your reply has been sent successfully!');
    }

    /**
     * Close the specified inquiry.
     */
    public function close(Inquiry $inquiry)
    {
        // Check if the current user is the owner of the listing or an admin
        if (Auth::id() !== $inquiry->carListing->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $inquiry->status = 'closed';
        $inquiry->save();

        return redirect()->route('inquiries.index')
            ->with('success', 'The inquiry has been closed successfully!');
    }
}