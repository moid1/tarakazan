<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Coupon;
use App\Models\CustomerDetail;
use App\Models\RedeemCode;
use App\Models\User;
use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class WaiterController extends Controller
{
    public function index()
    {
        $businessOwner = BusinessOwner::where('user_id', auth()->user()->id)->first();

        $waiters = Waiter::where('business_owner_id', $businessOwner->id)->with('user')->get();
        return view('business_owners.waiter.index', compact('waiters'));
    }

    public function create()
    {
        return view('business_owners.waiter.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8', // Password is optional during update
        ]);

        $businessOwnerId = BusinessOwner::where('user_id', auth()->user()->id)->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => 'waiter',
            'password' => \Hash::make($request->password), // Ensure the password is hashed
        ]);

        Waiter::create([
            'user_id' => $user->id,
            'business_owner_id' => $businessOwnerId->id,
        ]);
        Session::flash('success', 'Waiter created successfully');
        return redirect()->route('waiter.create');

    }

    public function destroy($id)
    {
        $businessOwner = BusinessOwner::where('user_id', auth()->user()->id)->first();

        $waiter = Waiter::find($id);
        if ($waiter && $waiter->business_owner_id === $businessOwner->id) {
            $userid = $waiter->user_id;
            $waiter->delete();
            User::find($userid)->delete();
            Session::flash('success', 'Waiter Deleted successfully');
            return redirect()->route('waiter.index');
        }
        dd("You are not allowed to this action");
    }

    public function verifyCouponCode(Request $request)
    {
        // dd($request->all());
        $waiter = Waiter::where('user_id', Auth::id())->first();
        $couponCode = $request->coupon_code;
        $phoneNo = $request->phone_no;
        $businessOwner = BusinessOwner::findOrFail($waiter->business_owner_id);
        $businessOwnerUserId = $businessOwner->user_id;

        // Retrieve coupon by code
        $coupon = Coupon::where('code', $couponCode)->first();
        if (!$coupon) {
            return back()->with('error', 'Invalid Coupon Code');
        }
        if ($businessOwnerUserId !== $coupon->user_id) {
            return back()->with('error', 'Invalid Coupon Code');

        }



        // Check if the coupon has expired
        if ($coupon->expiry_date && $coupon->expiry_date < now()) {
            return back()->with('error', 'Coupon Code has expired');
        }

        // Check if the coupon has already been redeemed for the given phone number
        if (RedeemCode::where([['coupon_id', $coupon->id], ['phone_no', $phoneNo]])->exists()) {
            return back()->with('error', 'You have already redeemed this coupon');
        }

        // Check if the customer exists in the system
        $customer = CustomerDetail::where('phone', $phoneNo)
            ->where('business_owner_id', $businessOwner->id)
            ->first();

        if (!$customer) {
            return back()->with('error', 'You are not in our system');
        }

        // Create a new redeem entry
        RedeemCode::create([
            'coupon_id' => $coupon->id,
            'phone_no' => $phoneNo,
            'customer_details_id' => $customer->id, // Assuming you want to associate the redemption with a customer
        ]);

        // Return a success message
        return back()->with('success', 'Coupon redeemed successfully');
    }

    public function removeFromBlackList(Request $request)
    {
        $waiter = Waiter::where('user_id', Auth::id())->first();
        if (!$waiter) {
            return;
        }


        $businessOwner = BusinessOwner::findOrFail($waiter->business_owner_id);
        $usercode = htmlspecialchars($businessOwner->sms_user_code, ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($businessOwner->sms_user_password, ENT_QUOTES, 'UTF-8');
        $appkey = htmlspecialchars($businessOwner->app_key, ENT_QUOTES, 'UTF-8');
        $xml = <<<XML
<?xml version="1.0"?>
<mainbody>
<header>
   <usercode>{$usercode}</usercode>
   <password>{$password}</password>
   <tip>2</tip>
   <appkey>{$appkey}</appkey>
</header>
<body>
   <number>{$request->phone}</number>

</body>
</mainbody>
XML;



        \Log::info($xml);

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://api.netgsm.com.tr/sms/blacklist'); // The API endpoint
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
        curl_setopt($ch, CURLOPT_POST, true);  // Use POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);  // Send the raw XML data as the body
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml',  // Set content type to XML
        ]);

        // Execute the cURL request
        $response = curl_exec($ch);
        \Log::info($response);

       
        return back()->with('successforBlacklist', 'Successfully removed from blacklist');

    }

}
