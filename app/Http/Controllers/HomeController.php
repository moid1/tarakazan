<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\CustomerDetail;
use App\Models\Package;
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
            return view('home', compact('totalActiveBusiness', 'months', 'counts', 'packageNames', 'packageCounts'));
        }

        $userId = \Auth::id(); // Get the logged-in user's ID
        $businessOwner = BusinessOwner::where('user_id', $userId)->first(); // Get the first matching BusinessOwner
        
        if ($businessOwner) {
            $businessOwnerId = $businessOwner->id;
        } else {
            $businessOwnerId = null;
        }

        $customersCount = CustomerDetail::where('business_owner_id', $businessOwnerId)->count();
        
        return view('business_owners.home', compact('customersCount','businessOwner'));
    }
}
