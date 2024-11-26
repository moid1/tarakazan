<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\CustomerDetail;
use App\Models\Package;
use App\Models\SMSQuota;
use App\Models\Subscription;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->user_type === 'admin') {
            // Get the number of business owners added each month
            $monthlyData = BusinessOwner::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get();

            // Prepare data for Chart.js
            $months = [];
            $counts = [];

            $allMonths = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];
            $monthlyCounts = array_fill(0, 12, 0);


            foreach ($monthlyData as $data) {
                // Subtract 1 from the month to match 0-based index of the $monthlyCounts array
                $monthlyCounts[$data->month - 1] = $data->total;
            }
            $months = $allMonths;
            $counts = $monthlyCounts;
            $totalActiveBusiness = BusinessOwner::where('business_status', 'active')->count();

            $packageData = BusinessOwner::select('package', DB::raw('COUNT(*) as total'))
                ->groupBy('package')
                ->join('packages', 'business_owners.package', '=', 'packages.id')
                ->get();

            // Prepare data for Chart.js
            $packageNames = [];
            $packageCounts = [];

            foreach ($packageData as $data) {
                $package = Package::find($data->package);
                $packageNames[] = $package->name; // Name of the package
                $packageCounts[] = $data->total; // Count of business owners for the package
            }

            $subscriptions = Subscription::all();
            $totalRevenue = 0;
            foreach ($subscriptions as $key => $subscription) {
              $totalRevenue+=floatval($subscription->package->price);
            }

            $customerCount = CustomerDetail::count();
            return view('home', compact('totalActiveBusiness', 'months', 'counts', 'packageNames', 'packageCounts', 'totalRevenue', 'customerCount'));
        }

        $userId = \Auth::id(); // Get the logged-in user's ID
        $businessOwner = BusinessOwner::where('user_id', $userId)->first(); // Get the first matching BusinessOwner

        if ($businessOwner) {
            $businessOwnerId = $businessOwner->id;
        } else {
            $businessOwnerId = null;
        }

        $customersCount = CustomerDetail::where('business_owner_id', $businessOwnerId)->count();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $package = Package::find($businessOwner->package);

        $smsCount = SMSQuota::where('business_owner_id', $businessOwnerId)
            ->whereMonth('created_at', $currentMonth)  // Filter by current month
            ->whereYear('created_at', $currentYear)    // Filter by current year
            ->count();  // Count the records        
        
        $totalSMSRemaining = intVal($package->quantity) - $smsCount;
        $user = auth()->user();

        return view('business_owners.home', compact('customersCount', 'businessOwner', 'smsCount','totalSMSRemaining','user'));
    }
}
