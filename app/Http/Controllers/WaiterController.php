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

}
