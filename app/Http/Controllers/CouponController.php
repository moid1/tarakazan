<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\RedeemCode;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::where('user_id', \Auth::id())->get();

        $redemptions = RedeemCode::selectRaw('coupon_id, DATE(created_at) as date, HOUR(created_at) as hour, count(*) as total')
            ->whereIn('coupon_id', $coupons->pluck('id'))  // Filter by coupon ids
            ->groupBy('coupon_id', 'date', 'hour') // Group by coupon, date, and hour
            ->orderBy('coupon_id', 'asc')          // Order by coupon_id
            ->orderBy('date', 'asc')               // Order by date to ensure we see all days
            ->orderBy('hour', 'asc')               // Order by hour so that we get data for each hour
            ->get();

        // Calculate total redemptions per coupon
        $totalRedemptions = $redemptions->groupBy('coupon_id')->map(function ($group) {
            return $group->sum('total');  // Sum the 'total' count for each coupon
        });

        // Append total redemptions to each coupon
        $coupons->each(function ($coupon) use ($totalRedemptions) {
            // Append the total redemptions count to each coupon object
            $coupon->total_redemptions = $totalRedemptions[$coupon->id] ?? 0;
        });

        // dd($coupons);

        $mostFrequentRedemptionTimes = $this->getMostFrequentRedemptionTimes($redemptions);

        return view('business_owners.coupons.index', compact('coupons', 'redemptions', 'mostFrequentRedemptionTimes'));
    }

    private function getMostFrequentRedemptionTimes($redemptions)
    {
        // Initialize an array to store the most frequent redemption for each coupon
        $mostFrequentTimes = [];

        // Loop through each redemption entry
        foreach ($redemptions as $redemption) {
            // Check if this coupon_id has already been processed
            if (!isset($mostFrequentTimes[$redemption->coupon_id])) {
                // If not, initialize it with the first redemption data
                $mostFrequentTimes[$redemption->coupon_id] = $redemption;
            } else {
                // If it has, check if the current redemption has a higher 'total' count
                if ($redemption->total > $mostFrequentTimes[$redemption->coupon_id]->total) {
                    // If so, update it
                    $mostFrequentTimes[$redemption->coupon_id] = $redemption;
                }
            }
        }

        // Now transform the mostFrequentTimes to a simple indexed array
        $result = [];
        foreach ($mostFrequentTimes as $redemption) {
            // We directly add the formatted result here
            $result[] = [
                'coupon' => Coupon::find($redemption->coupon_id)->code,  // Get the coupon code
                'date' => $redemption->date,                               // Redemption date
                'hour' => $redemption->hour,                               // Redemption hour
                'total' => $redemption->total,                             // Redemption count
            ];
        }

        // Return the flat result as a simple indexed array
        return $result;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campaigns = Campaign::where('user_id', \Auth::id())->get();
        return view('business_owners.coupons.create',compact('campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code', // Ensure code is unique in the packages table
            'expiry_date' => 'required|date|after_or_equal:today',  // Ensure it's a valid date and not in the past
            'gift' => 'required|string|max:255',
            'campaign_id'=>'required'
        ]);
        $validatedData['user_id'] = auth()->id(); // Assuming you're using Laravel's built-in authentication

        Coupon::create($validatedData);
        return redirect()->route('coupon.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon && $coupon->user_id == \Auth::id()) { // Check if coupon exists and belongs to the user
            $coupon->delete();
        }
        return back();
    }

    public function makeDefault($id)
    {
        // Set all coupons of the current user to 'is_default' = false
        Coupon::where('user_id', \Auth::id())->update(['is_default' => false]);

        // Set the specified coupon's 'is_default' to true
        $coupon = Coupon::find($id);
        if ($coupon && $coupon->user_id == \Auth::id()) { // Check if coupon exists and belongs to the user
            $coupon->update(['is_default' => true]);
        }
        return back();
    }
}
