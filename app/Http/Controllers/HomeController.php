<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Coupon;
use App\Models\CustomerDetail;
use App\Models\Package;
use App\Models\RedeemCode;
use App\Models\SMSQuota;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Waiter;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $totalActiveBusiness = User::where('user_type', 'business_owner')
                ->where('is_block', false)
                ->count();

            $totalInActiveBusiness = User::where('user_type', 'business_owner')
                ->where('is_block', true)
                ->count();

            $packageData = BusinessOwner::select('package', DB::raw('COUNT(*) as total'))
                ->groupBy('package')
                ->join('packages', 'business_owners.package', '=', 'packages.id')
                ->get();

            $totalQRScanCount = BusinessOwner::sum('qr_scan_count');
            $totalReviewsCount = BusinessOwner::sum('google_reviews');

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
                $totalRevenue += floatval($subscription->package->price);
            }

            $customerCount = CustomerDetail::count();
            $monthlyRevenueData = $this->monthlyRevenueReport();

            $topPerformers = $this->getTopPerformingBusinessOwners();
            $getMostFrequentRegistrationHour = $this->getMostFrequentRegistrationHour();
            // dD($getMostFrequentRegistrationHour);

            return view('home', compact(
                'totalActiveBusiness',
                'months',
                'counts',
                'packageNames',
                'packageCounts',
                'totalRevenue',
                'customerCount',
                'totalInActiveBusiness',
                'totalQRScanCount',
                'totalReviewsCount',
                'monthlyRevenueData',
                'topPerformers',
                'getMostFrequentRegistrationHour'
            ));
        } else if (auth()->user()->user_type === 'business_owner') {
            $totalSMSRemaining = 0;
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
            $subscription = Subscription::where('user_id', Auth::id())->latest()->first();
            if ($subscription) {
                $smsCount = SMSQuota::where([['business_owner_id', $businessOwnerId], ['subscription_id', $subscription->id]])
                    ->whereMonth('created_at', $currentMonth)  // Filter by current month
                    ->whereYear('created_at', $currentYear)    // Filter by current year
                    ->count();  // Count the records        

                $totalSMSRemaining = intVal($package->quantity) - $smsCount;
            }
            $user = auth()->user();

            $couponIds = Coupon::where('user_id', \Auth::id())->pluck('id');
            $redeemCodeCount = RedeemCode::whereIn('coupon_id', $couponIds)->count();

            return view('business_owners.home', compact('customersCount', 'businessOwner', 'smsCount', 'totalSMSRemaining', 'user', 'redeemCodeCount', 'subscription'));
        } else {

            $waiterData = Waiter::where('user_id', Auth::id())->first();
            $businessOwner = BusinessOwner::find($waiterData->business_owner_id);
            return view('waiter.verification', compact('businessOwner'));
        }
    }

    public function monthlyRevenueReport()
    {
        // Get the current month and previous month
        $currentMonth = Carbon::now()->month;
        $previousMonth = Carbon::now()->subMonth()->month;

        // Calculate Total Revenue for the current month
        $currentMonthRevenue = Subscription::whereMonth('subscriptions.created_at', $currentMonth)
            ->join('packages', 'subscriptions.package_id', '=', 'packages.id')  // Join with packages table
            ->sum('packages.price');  // Sum the price of the packages

        $previousMonthRevenue = Subscription::whereMonth('subscriptions.created_at', $previousMonth)
            ->join('packages', 'subscriptions.package_id', '=', 'packages.id')  // Join with packages table
            ->sum('packages.price');  // Sum the price of the packages

        // Breakdown revenue by subscription level (package name) for the current month
        $currentMonthRevenueByLevel = Subscription::whereMonth('subscriptions.created_at', $currentMonth)
            ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->selectRaw('packages.name as package_name, SUM(packages.price) as total_revenue')
            ->groupBy('packages.name')
            ->get();

        // Breakdown revenue by subscription level (package name) for the previous month
        $previousMonthRevenueByLevel = Subscription::whereMonth('subscriptions.created_at', $previousMonth)
            ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->selectRaw('packages.name as package_name, SUM(packages.price) as total_revenue')
            ->groupBy('packages.name')
            ->get();


        return [
            'currentMonthRevenue' => $currentMonthRevenue,
            'previousMonthRevenue' => $previousMonthRevenue,
            'currentMonthRevenueByLevel' => $currentMonthRevenueByLevel,
            'previousMonthRevenueByLevel' => $previousMonthRevenueByLevel
        ];
    }

    public function getTopPerformingBusinessOwners()
    {
        $topPerformers = BusinessOwner::select('id', 'business_name', 'qr_scan_count', 'google_reviews')
            ->orderByDesc('qr_scan_count') // Sort by QR scan count in descending order
            ->orderByDesc('google_reviews') // Sort by Google review count in descending order
            ->take(10) // Get top 10 business owners
            ->get();
        return $topPerformers;
    }

    public function formatHour($hour)
    {
        // Convert 24-hour format to 12-hour format with AM/PM
        return date("g A", strtotime("$hour:00"));
    }
    public function getMostFrequentRegistrationHour()
    {
        $registrationTimes = CustomerDetail::selectRaw('HOUR(created_at) as registration_hour, COUNT(*) as count')
            ->groupByRaw('HOUR(created_at)') // Group by the hour only (not full timestamp)
            ->orderByDesc('count') // Order by count, descending
            ->get()
            ->map(function ($item) {
                // Convert hour to 12-hour format with AM/PM
                $item->registration_hour = $this->formatHour($item->registration_hour);
                return $item;
            });

        return $registrationTimes;
    }
}
