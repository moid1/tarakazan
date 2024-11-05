<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr; 

class BusinessOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businessOwners = BusinessOwner::all();
        return view('admin.business_owners.index', compact('businessOwners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $packages = Package::all();
        return view('admin.business_owners.create', compact('packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|unique:business_owners,business_email',
            'package' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'slug' => 'required|string|max:255|unique:business_owners,slug',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'facebook' => 'nullable|url',
            'tiktok' => 'nullable|url',
            'instagram' => 'nullable|url',
            'password' => 'required|string|min:8', // Confirmed password validation
        ]);
    
        // Handle the logo upload (if exists)
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public'); // Store in public/logos
            $validatedData['logo'] = $logoPath; // Add the path of the uploaded file
        }
    
        // Create the user first (this is where the password is hashed)
        $user = User::create([
            'name' => $request->business_name,
            'email' => $request->business_email,
            'password' => \Hash::make($request->password), // Ensure the password is hashed
        ]);
    
        // Create a new BusinessOwner entry (excluding the password field)
        $business = new BusinessOwner(Arr::except($validatedData, ['password', 'password_confirmation'])); // Exclude password from the $validatedData
        $business->user_id = $user->id; // Associate the business with the created user
        $business->save(); // Save the business to the database
    
        // You might want to return a response or redirect the user
        return redirect()->route('admin.business.owner.index')->with('success', 'Business Owner created successfully!');
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
