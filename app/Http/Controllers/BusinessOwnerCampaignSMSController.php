<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\BusinessOwnerCampaignSMS;
use App\Models\Campaign;
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
        $businessOnwerCampaigns = BusinessOwnerCampaignSMS::where('business_owner_id', $businessOwner->id)->get();
        return view('business_owners.sms.index', compact('businessOnwerCampaigns'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campaigns = Campaign::where('user_id', Auth::id())->get();
        return view('business_owners.sms.create', compact('campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validatedData = $request->validate([
            'message' => 'required|string|max:1600', // max length for SMS
            'delivery_time' => 'required|date|after:now', // Ensure delivery time is in the future
            'campaign_id' => 'required'
        ]);

        $businessOwner = BusinessOwner::where('user_id', \Auth::id())->first();
        // You can save the campaign details to the database, if needed
        $campaign = new BusinessOwnerCampaignSMS();
        $campaign->sms = $validatedData['message'];
        $campaign->delivery_date = $validatedData['delivery_time'];
        $campaign->campaigns_id = $validatedData['campaign_id'];
        $campaign->business_owner_id = $businessOwner->id;
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
            Session::flash('success', 'Campaign SMS deleted successfully!');
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