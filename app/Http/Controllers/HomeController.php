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
                // Check if the subscription has a valid package
                if ($subscription->package) {
                    // Add the package price to the total revenue
                    $totalRevenue += floatval($subscription->package->price);
                } else {
                    // If no package is available, add 0
                    $totalRevenue += 0;
                }
            }
            
            $customerCount = CustomerDetail::where('is_verified', true)->count();


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
            $otpSmsRemaining = 0;
            $smsCount = 0;
            $userId = \Auth::id(); // Get the logged-in user's ID
            $businessOwner = BusinessOwner::where('user_id', $userId)->first(); // Get the first matching BusinessOwner

            if ($businessOwner) {
                $businessOwnerId = $businessOwner->id;
            } else {
                $businessOwnerId = null;
            }

            $customersCount = CustomerDetail::where([['business_owner_id', $businessOwnerId], ['is_verified', true]])->count();

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $package = Package::find($businessOwner->package);
            $subscription = Subscription::where('user_id', Auth::id())->latest()->first();
            if ($subscription) {
                $otpSmsCount = SMSQuota::where([
                    ['business_owner_id', $businessOwnerId],
                    ['subscription_id', $subscription->id]
                ])
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->whereRaw("sms REGEXP '^[0-9]{6}$'")  // Filter for 6-digit OTP messages
                    ->count();


                $sentSMSBYBO = SMSQuota::where([
                    ['business_owner_id', $businessOwnerId],
                    ['subscription_id', $subscription->id]
                ])
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->whereRaw("sms NOT REGEXP '^[0-9]{6}$'")  // Filter for non-OTP messages
                    ->get();


                foreach ($sentSMSBYBO as $key => $value) {
                    $smsCount += (int) $value->sms_limit;
                }


                $totalSMSRemaining = intVal($package->quantity) - $smsCount;
                $otpSmsRemaining = max(0, 1000 - $otpSmsCount);

            }
            $user = auth()->user();

            $couponIds = Coupon::where('user_id', \Auth::id())->pluck('id');
            $redeemCodeCount = RedeemCode::whereIn('coupon_id', $couponIds)->count();

            $customerGrowth = DB::table('customer_details')
                ->selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, COUNT(*) as total')
                ->where('created_at', '>=', now()->subWeeks(6)) // Get the last 6 weeks
                ->where('business_owner_id', $businessOwnerId)
                ->groupBy('year', 'week')  // Group by both year and week number
                ->orderBy('year', 'desc')  // Sort by year and week (most recent first)
                ->orderBy('week', 'desc')
                ->get();
            $weeks = $customerGrowth->map(function ($item) {
                return $item->week; // Format as Week 1 (2024)
            });

            // Extract week numbers and totals
            // $weeks = $customerGrowth->pluck('week');
            $totals = $customerGrowth->pluck('total');

            //// Next Package
            $isNextPackage = false;

            $lifeTimeSMSSent =  0;
             $lifeTimeSMSSent = SMSQuota::where('business_owner_id', $businessOwner->id)
            ->count();

            if ($subscription && ($subscription->status === 'active' && $subscription->end_date >= now())) {
                // Subscription is valid, proceed with the logic
    
                // Retrieve the business owner, package, and SMS count in a more efficient manner
                $businessOwner = BusinessOwner::where('user_id', Auth::id())->first();
                if (!$businessOwner) {
                    return back()->with('error', 'Business owner profile not found.');
                }
    
                // Get the package details for the business owner
                $package = $businessOwner->package ? Package::find($businessOwner->package) : null;
    
                // Ensure package exists before proceeding
                if (!$package) {
                    return back()->with('error', 'Invalid package associated with your business.');
                }
    
                // Calculate the remaining SMS count for the current month
                $currentMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;
                $smsCount = SMSQuota::where('business_owner_id', $businessOwner->id)
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->count();
    
                // Calculate total remaining SMS
                $totalSMSRemaining = max(0, $package->quantity - $smsCount);
                if ($totalSMSRemaining === 0) {
                  $isNextPackage = true;
                  $subscription->update(['status' => 'inactive']); // Efficient status update
                  Auth::user()->update(['is_paid' => false]);
                }
    
                //checkk if Customer Count exceeds
                $customersCount = CustomerDetail::where([['business_owner_id', $businessOwner->id], ['is_verified', true]])->count();
                if (intval($package->customers) <= $customersCount) {
                    $isNextPackage = true;
                    $subscription->update(['status' => 'inactive']); // Efficient status update
                    Auth::user()->update(['is_paid' => false]);
                }
            }


            return view('business_owners.home', compact('customersCount', 'businessOwner', 'smsCount', 'totalSMSRemaining', 'user', 'redeemCodeCount', 'subscription', 'otpSmsRemaining', 'weeks', 'totals', 'package','isNextPackage', 'lifeTimeSMSSent'));
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

    public function deleteCustomer($id)
    {
        CustomerDetail::find($id)->delete();
        return back()->with('success', 'Customer Deleted Successfully');
    }
}
