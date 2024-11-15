<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\SocialMedia;
use App\Models\SocialMediaCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the logged-in user's ID
        $userId = Auth::id();

        // Find the business owner based on the logged-in user ID
        $businessOwner = BusinessOwner::where('user_id', $userId)->first();

        // Check if the business owner exists
        if (!$businessOwner) {
            return redirect()->route('home')->with('error', 'Business owner not found.');
        }

        // Fetch click counts for each platform specific to the business owner
        $facebookClicks = SocialMediaCount::where('platform', 'facebook')
            ->where('business_owner_id', $businessOwner->id) // Use $businessOwner->id
            ->count();

        $tiktokClicks = SocialMediaCount::where('platform', 'tiktok')
            ->where('business_owner_id', $businessOwner->id) // Use $businessOwner->id
            ->count();

        $instagramClicks = SocialMediaCount::where('platform', 'instagram')
            ->where('business_owner_id', $businessOwner->id) // Use $businessOwner->id
            ->count();

        // Return the view with the counts for each platform
        return view('business_owners.social_media.index', compact('facebookClicks', 'tiktokClicks', 'instagramClicks'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialMedia $socialMedia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialMedia $socialMedia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialMedia $socialMedia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMedia $socialMedia)
    {
        //
    }

    public function updateSocialMediaCount(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'platform' => 'required|string|in:facebook,instagram,tiktok',
            'count' => 'required|integer|min:0',
            'business_owner_id' => 'required|integer', // Validate business owner ID
        ]);

        // Get the platform and count from the request
        $platform = $request->input('platform');
        $count = $request->input('count');
        $businessOwnerId = $request->input('business_owner_id'); // Business owner ID passed from the frontend

        // Ensure the business owner ID is valid (optional check)
        if (!$businessOwnerId || $businessOwnerId != Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized action'], 403);
        }
        // Update or insert the count for the platform and business owner
// Update or insert the count for the platform and business owner
        SocialMediaCount::updateOrInsert(
            ['business_owner_id' => $businessOwnerId, 'platform' => $platform],
            ['count' => $count]
        );
        // Return a success response
        return response()->json(['status' => 'success'], 200);
    }
}
