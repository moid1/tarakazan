<?php

namespace App\Console\Commands;

use App\Models\BusinessOwnerCampaignSMS;
use App\Models\CustomerDetail;
use App\Models\SMSQuota;
use Illuminate\Console\Command;
use App\Models\BusinessOwner;
use Carbon\Carbon;

class SendSmsCampaigns extends Command
{
    // The name and signature of the console command
    protected $signature = 'sms:send-campaigns';

    // The console command description
    protected $description = 'Fetch campaigns and send SMS to the recipients.';

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    // Execute the console command
    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d H:i');  // Get current time formatted to 'YYYY-MM-DD HH:MM'
        echo $now;

        // Fetch campaigns scheduled to be sent today and at the current time (ignoring seconds)
        $campaigns = BusinessOwnerCampaignSMS::whereDate('delivery_date', Carbon::today())
            ->whereTime('delivery_date', '=', $now)
            ->where('is_sent', false)
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No campaigns to process at this time.');
            return;
        }

        // Fetch all business owners needed for the campaigns at once
        $businessOwners = BusinessOwner::find($campaigns->pluck('business_owner_id')->unique()->toArray())->keyBy('id');

        // Iterate over each campaign
        foreach ($campaigns as $campaign) {
            // Get business owner
            $businessOwner = $businessOwners[$campaign->business_owner_id];

            // Fetch verified recipients (only once per campaign's business owner)
            $recipients = CustomerDetail::where('business_owner_id', $campaign->business_owner_id)
                ->where('is_verified', true)
                ->pluck('phone');

            // Skip campaign if no recipients
            if ($recipients->isEmpty()) {
                $this->info("No verified recipients found for campaign ID: {$campaign->id}");
                continue;
            }

            // Call the method to send SMS
            $this->sendCampaignSMS($recipients, $campaign->sms, $businessOwner);

            // Mark campaign as sent
            $campaign->update(['is_sent' => true]);

            $this->info("SMS sent for campaign ID: {$campaign->id}");
        }

        $this->info('Campaigns processed and SMS sent!');
    }

    // Send SMS using the XML payload and cURL (Netgsm API)


    public function sendCampaignSMS($recipients, $message, $businessOwner)
    {
        // Escape values to prevent XML injection
        $usercode = htmlspecialchars($businessOwner->sms_user_code, ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($businessOwner->sms_user_password, ENT_QUOTES, 'UTF-8');
        $msgheader = htmlspecialchars($businessOwner->sms_message_header, ENT_QUOTES, 'UTF-8');
        $appkey = htmlspecialchars($businessOwner->app_key, ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        // Create XML payload
        $xml = <<<XML
    <?xml version="1.0" encoding="UTF-8"?>
    <mainbody>
       <header>
           <usercode>{$usercode}</usercode>
           <password>{$password}</password>
           <msgheader>{$msgheader}</msgheader>
           <type>1:n</type>
           <appkey>{$appkey}</appkey>
       </header>
       <body>
           <msg><![CDATA[{$message}]]></msg>
    XML;

        // Loop through recipients and add their numbers to the XML
        foreach ($recipients as $recipient) {
            $recipient = htmlspecialchars($recipient, ENT_QUOTES, 'UTF-8');
            $xml .= "<no>{$recipient}</no>";
        }

        $xml .= '</body></mainbody>';

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

        \Log::info('Sending XML: ' . $xml);  // Log the XML for debugging purposes

        // Perform a single bulk insert into the SMSQuota table
        $data = [];
        foreach ($recipients as $value) {
            $data[] = [
                'phone' => $value,
                'sms' => $message,
                'business_owner_id' => $businessOwner->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert
        SMSQuota::insert($data);

        // Execute the cURL request and capture the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            \Log::error('cURL Error: ' . curl_error($ch));
            $this->error("SMS sending failed due to a cURL error.");
        } else {
            \Log::info('Netgsm Response: ' . $response);
            // You can optionally process the response from Netgsm here if needed
            $this->info("SMS sent successfully!");
        }

        // Close the cURL session
        curl_close($ch);
    }
}