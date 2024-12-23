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
        $packages = Package::all();

        return view('admin.business_owners.index', compact('businessOwners', 'packages'));
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
            'sms_user_code' => 'required',
            'sms_user_password' => 'required',
            'sms_message_header' => 'required',
            'google_review' => 'required',
            'mersis_no' => 'required',
            'phone_number_netgsm' => 'required',
            'google_review_before'=>'required',
            'google_review_after'=>'required',
            'stop_link' => 'required'
        ]);

        // Handle the logo upload (if exists)
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public'); // Store in public/logos
            $validatedData['logo'] = $logoPath; // Add the path of the uploaded file
        }


        if ($request->hasFile('google_review_path')) {
            $google_review_path = $request->file('google_review_path')->store('google_review_path', 'public'); // Store in public/logos
            $validatedData['google_review_path'] = $google_review_path; // Add the path of the uploaded file
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
        $timestamp = now()->timestamp;  // or use time() for a UNIX timestamp
        $qrCodeFileName = $business->slug . '_' . $timestamp . '.png';

        $qrCodePath = 'qrcodes/' . $qrCodeFileName;
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
            'package' => 'required',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'facebook' => 'nullable|url',
            'tiktok' => 'nullable|url',
            'instagram' => 'nullable|url',
            'password' => 'nullable|string|min:8', // Confirmed password validation,
            'app_key' => 'required',
            'sms_user_code' => 'required',
            'sms_user_password' => 'required',
            'sms_message_header' => 'required',
            'google_review' => 'required',
            'mersis_no' => 'required',
            'phone_number_netgsm' => 'required',
            'stop_link' => 'required',
            'google_review_before'=>'required',
            'google_review_after'=>'required'
        ]);





        // If a logo file is uploaded, store it and update the path in the database
        if ($request->hasFile('logo')) {
            $validatedData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('google_review_path')) {
            $google_review_path = $request->file('google_review_path')->store('google_review_path', 'public'); // Store in public/logos
            $validatedData['google_review_path'] = $google_review_path; // Add the path of the uploaded file
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
            'package' => $validatedData['package'], // Ensure this is correct
            'address' => $validatedData['address'],
            'country' => $validatedData['country'],
            'sms_user_code' => $validatedData['sms_user_code'],
            'sms_user_password' => $validatedData['sms_user_password'],
            'sms_message_header' => $validatedData['sms_message_header'],
            'postal_code' => $validatedData['postal_code'],
            'facebook' => $validatedData['facebook'] ?? null,
            'tiktok' => $validatedData['tiktok'] ?? null,
            'instagram' => $validatedData['instagram'] ?? null,
            'mersis_no' => $validatedData['mersis_no'] ?? null,
            'phone_number_netgsm' => $validatedData['phone_number_netgsm'] ?? null,
            'stop_link' => $validatedData['stop_link'] ?? null,
            'logo' => $validatedData['logo'] ?? $businessOwner->logo, // Keep the existing logo if not updated
            'google_review_before'=>$validatedData['google_review_before'],
            'google_review_after'=>$validatedData['google_review_after'],
            'google_review_path' => $validatedData['google_review_path'] ?? $businessOwner->google_review_path, // Keep the existing logo if not updated
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
