<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\BusinessOwnerCampaignSMS;
use App\Models\Campaign;
use App\Models\CustomerDetail;
use App\Models\Package;
use App\Models\SMSQuota;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class BusinessOwnerCampaignSMSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businessOwner = BusinessOwner::where('user_id', \Auth::id())->first();
        $businessOnwerCampaigns = BusinessOwnerCampaignSMS::where('business_owner_id', $businessOwner->id)->latest()->get();
        return view('business_owners.sms.index', compact('businessOnwerCampaigns'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch the latest subscription for the current user
        $subscription = Subscription::where('user_id', Auth::id())->latest()->first();

        // Check if the subscription exists and is active within the valid date range
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
                $subscription->update(['status' => 'inactive']); // Efficient status update
                Auth::user()->update(['is_paid' => false]);
                return back()->with([
                    'error' => 'You have no SMS remaining for this month.',
                    'nextpackage' => true
                ]);
            }

            //checkk if Customer Count exceeds
            $customersCount = CustomerDetail::where([['business_owner_id', $businessOwner->id], ['is_verified', true]])->count();
            if (intval($package->customers) <= $customersCount) {
                $subscription->update(['status' => 'inactive']); // Efficient status update
                Auth::user()->update(['is_paid' => false]);
                return back()->with([
                    'error' => 'You have Exceed Customer Limits',
                    'nextpackage' => true
                ]);
            }
            // Fetch campaigns for the user
            $campaigns = Campaign::where('user_id', Auth::id())->get();

            return view('business_owners.sms.create', compact('campaigns'));
        }

        // If the subscription is expired or inactive, update the status and handle the error
        if ($subscription) {
            $subscription->update(['status' => 'inactive']); // Efficient status update
        }

        // Update the user status to reflect inactive subscription
        Auth::user()->update(['is_paid' => false]);

        // Return with an error message
        return back()->with('error', trans('messages.Your subscription is inactive or has expired, so you are not authorized to access this.'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // dd($request->all());
        // Validation
        $validatedData = $request->validate([
            'message' => 'required|string|max:1600', // max length for SMS
            'delivery_time' => 'required|date|after:now', // Ensure delivery time is in the future
            'campaign_id' => 'required',
            'sms_limit'=>'required',
            'duration'=>'required',
            'customers_type'=>'required'
        ]);


        $businessOwner = BusinessOwner::where('user_id', \Auth::id())->first();
        // You can save the campaign details to the database, if needed
        $campaign = new BusinessOwnerCampaignSMS();
        $campaign->sms = $request->message;
        $campaign->delivery_date = $validatedData['delivery_time'];
        $campaign->campaigns_id = $validatedData['campaign_id'];
        $campaign->sms_limit = $validatedData['sms_limit'];
        $campaign->business_owner_id = $businessOwner->id;
        $campaign->duration = $validatedData['duration'];
        $campaign->customers_type = $validatedData['customers_type'];
        $campaign->save();

        // $this->sendCampaignSMS();

        return redirect()->route('campaign.sms.index')->with('success', 'Campaign scheduled successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessOwnerCampaignSMS $businessOwnerCampaignSMS)
    {
        //
    }
    public function edit($id)
    {
        // Fetch the existing campaign SMS data
        $campaignSms = BusinessOwnerCampaignSMS::findOrFail($id);
        $campaigns = Campaign::where('user_id', Auth::id())->get();

        return view('business_owners.sms.edit', compact('campaignSms', 'campaigns'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'message' => 'required|string|max:1600',
            'delivery_time' => 'required|date|after_or_equal:now',
        ]);

        try {
            // Find the campaign SMS record
            $campaignSms = BusinessOwnerCampaignSMS::findOrFail($id);

            // Update the SMS campaign data
            $campaignSms->campaigns_id = $request->campaign_id;
            $campaignSms->sms = $request->message;
            $campaignSms->delivery_date = $request->delivery_time;
            $campaignSms->save();

            // Flash success message
            Session::flash('success', 'Campaign SMS updated successfully!');
            return redirect()->route('campaign.sms.edit', $campaignSms->id);
        } catch (\Exception $e) {
            // Handle errors
            Session::flash('error', 'Something went wrong. Please try again.');
            return redirect()->route('campaign.sms.edit', $id)->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the SMS campaign by its ID
            $campaignSms = BusinessOwnerCampaignSMS::findOrFail($id);

            // Delete the campaign SMS
            $campaignSms->delete();

            // Flash a success message
            Session::flash('success', trans('messages.Campaign SMS deleted successfully!'));
        } catch (\Exception $e) {
            // If something goes wrong, flash an error message
            Session::flash('error', 'Something went wrong. Please try again.');
        }

        // Redirect back to the list or to a specific page
        return redirect()->route('campaign.sms.index');  // Replace with the correct route
    }

    public function sendCampaignSMS()
    {
        // Assuming BusinessOwner ID is 7. Adjust as needed.
        $businessOwner = BusinessOwner::find(7);

        $xml = <<<XML
        <?xml version="1.0"?>
        <mainbody>
           <header>
               <usercode>{$businessOwner->sms_user_code}</usercode>
               <password>{$businessOwner->sms_user_password}</password>
               <msgheader>{$businessOwner->sms_message_header}</msgheader>
                <type>1:n</type>
               <appkey>{$businessOwner->app_key}</appkey>
           </header>
           <body>
               <msg><![CDATA[This is for testing purpose for multiple users]]></msg>
               <no>+90 5348580388</no>
               <no>+90 5010066200</no>

           </body>
        </mainbody>
        XML;



        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://api.netgsm.com.tr/sms/send/xml'); // The API endpoint
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
        curl_setopt($ch, CURLOPT_POST, true);  // Use POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);  // Send the raw XML data as the body
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml',  // Set content type to XML
        ]);

        // Execute the cURL request
        $response = curl_exec($ch);
        \Log::info($response);


        // Close the cURL session
        curl_close($ch);
        dd($response);



    }

}
