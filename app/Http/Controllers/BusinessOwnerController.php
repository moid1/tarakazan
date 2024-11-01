<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.business_owners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|unique:businesses,business_email',
            'package' => 'required|in:basic,premium,enterprise',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'slug' => 'required|string|max:255|unique:businesses,slug',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
        ]);

        // Handle the logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public'); // Store in public/logos
            $validatedData['logo'] = $logoPath;
        }

        // Create a new business entry
        $business = new BusinessOwner($validatedData);
        $business->user_id = Auth::id(); // Associate the business with the authenticated user
        $business->save(); // Save to the database
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessOwner $businessOwner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessOwner $businessOwner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessOwner $businessOwner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessOwner $businessOwner)
    {
        //
    }
}
