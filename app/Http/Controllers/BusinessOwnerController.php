<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Package;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BusinessOwnersExport;
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
            'slug' => 'required|string|max:255|unique:business_owners,slug',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'facebook' => 'nullable|url',
            'tiktok' => 'nullable|url',
            'instagram' => 'nullable|url',
            'password' => 'required|string|min:8', // Confirmed password validation,
            'app_key' => 'required',
            'sms_user_code'=>'required',
            'sms_user_password'=>'required',
            'sms_message_header'=>'required',
            'google_review' => 'required'
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

        $chatbotUrl = route('chatbot.show', ['slug' => $business->slug]);
        $qrCodeImage = QrCode::format('png')->size(300)->generate($chatbotUrl);
        // Save the QR code image to the public storage
        $qrCodePath = 'qrcodes/' . $business->slug . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCodeImage);
        $business->save(); // Save the business to the database

        // Save the QR code path to the database
        $business->update([
            'qr_code_path' => $qrCodePath
        ]);

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
        // Get all packages for the dropdown
        $packages = Package::all();

        // Return the view with the businessOwner data and packages
        return view('admin.business_owners.edit', compact('businessOwner', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessOwner $businessOwner)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'google_review' => 'required|string|max:255',
            'app_key' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'package' => 'required|exists:packages,id',
            'slug' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'facebook' => 'nullable|url',
            'tiktok' => 'nullable|url',
            'instagram' => 'nullable|url',
            'logo' => 'nullable|image',
            'password' => 'nullable|string|min:8', // Password is optional during update
        ]);

        // If a logo file is uploaded, store it and update the path in the database
        if ($request->hasFile('logo')) {
            $validatedData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // If a new password is provided, hash it
        if ($request->filled('password')) {
            $businessOwner->user->update([
                'password' => bcrypt($request->password)
            ]);
        }
        // Update the business owner with validated data
        $businessOwner->update([
            'business_name' => $validatedData['business_name'],
            'google_review' => $validatedData['google_review'],
            'app_key' => $validatedData['app_key'],
            'business_email' => $validatedData['business_email'],
            'package' => $validatedData['package'], // Ensure this is correct
            'slug' => $validatedData['slug'],
            'address' => $validatedData['address'],
            'country' => $validatedData['country'],
            'postal_code' => $validatedData['postal_code'],
            'facebook' => $validatedData['facebook'] ?? null,
            'tiktok' => $validatedData['tiktok'] ?? null,
            'instagram' => $validatedData['instagram'] ?? null,
            'logo' => $validatedData['logo'] ?? $businessOwner->logo, // Keep the existing logo if not updated
        ]);

        // Redirect to the business owners index with a success message
        return redirect()->route('admin.business.owner.index')->with('success', 'Business owner updated successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessOwner $businessOwner)
    {
        // Delete the business owner from the database
        $businessOwner->delete();

        // Redirect to the index page with a success message
        return redirect()->route('admin.business.owner.index')->with('success', 'Business owner deleted successfully!');
    }

    public function exportPdf()
    {
        // Fetch all business owners
        $businessOwners = BusinessOwner::all();

        // Generate the PDF
        $pdf = Pdf::loadView('admin.business_owners.pdf', compact('businessOwners'));

        // Return the PDF for download
        return $pdf->download('business_owners.pdf');
    }

    public function exportCsv()
    {
        return Excel::download(new BusinessOwnersExport, 'business_owners.csv');
    }

    // Show business owner's profile
    public function showProfile()
    {
        // Fetch the logged-in business owner
        $businessOwner = BusinessOwner::where('user_id', Auth::id())->first();

        if (!$businessOwner) {
            return redirect()->route('dashboard')->with('error', 'Business Owner not found.');
        }

        return view('business_owners.profile', compact('businessOwner'));
    }

    // Update business owner's profile
    public function updateProfile(Request $request)
    {
        $businessOwner = BusinessOwner::where('user_id', Auth::id())->first();

        if (!$businessOwner) {
            return redirect()->route('dashboard')->with('error', 'Business Owner not found.');
        }

        // Validate the incoming data
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|string|min:8',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'tiktok' => 'nullable|url',
        ]);

        $user = Auth::user();
        $businessOwner = $user->businessOwner;

        $businessOwner->update([
            'business_name' => $validated['business_name'],
            // 'google_review' => $validated['google_review'],
            // 'app_key' => $validated['app_key'],
            'business_email' => $validated['business_email'],
            // 'package_id' => $validated['package'],
            // 'slug' => $validated['slug'],
            'address' => $validated['address'],
            'country' => $validated['country'],
            'postal_code' => $validated['postal_code'],
            'facebook' => $validated['facebook'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'tiktok' => $validated['tiktok'] ?? null,
        ]);
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('business_owners_logos', 'public');
            $businessOwner->update(['logo' => $logoPath]);
        }

        // Handle password change (if provided)
        if ($request->filled('password')) {
            $businessOwner->user->update(['password' => bcrypt($validated['password'])]);
        }

        return redirect()->route('business-owner.profile')->with('success', 'Profile updated successfully.');
    }
}
