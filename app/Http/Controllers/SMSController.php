<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Coupon;
use App\Models\CustomerDetail;
use App\Models\SMSQuota;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;
class SMSController extends Controller
{
    public function sendOTPSMSToCustomer(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'phone_no' => 'required|string|regex:/^\+?\d{10,15}$/', // Phone number validation
            'name' => 'required|string|max:255',                    // Name validation
            'business_owner_id' => 'required'
        ]);

        if (!$validated) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data.',
            ], 400);
        }

        // Generate a random OTP (6 digits)
        $otp = rand(100000, 999999);

        $phoneNo = ltrim($request->phone_no, '0');  // Remove leading zeros

        $isAlreadyExists = CustomerDetail::where([['phone', $phoneNo], ['is_verified', true], ['business_owner_id', $request->business_owner_id]])->count();

        if ($isAlreadyExists > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işletmeye zaten kayıtlısın.',
            ], 400);
        }

        // Store customer details
        try {
            $customer = CustomerDetail::create([
                'name' => $request->name,
                'phone' => $phoneNo,
                'business_owner_id' => $request->business_owner_id,
                'otp' => $otp,  // Optionally, store OTP for later verification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage(),
            ], 500);
        }

        // Send OTP via SMS (Assuming you're using an SMS service like Twilio or Nexmo)
        $netGsmResponse = [];
        $businessOwnerUser = BusinessOwner::find($request->business_owner_id);
        $netGsmResponse = $this->sendSMSThroughXML($phoneNo, $otp, $request->business_owner_id);
        $subscription = Subscription::where('user_id', $businessOwnerUser->user_id)->latest()->first();
        try {

            // Bulk insert
            SMSQuota::create([
                'phone' => $phoneNo,
                'sms' => $otp,
                'subscription_id' => $subscription->id,
                'business_owner_id' => $request->business_owner_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Return a response indicating success
            return response()->json([
                'success' => true,
                'customer' => $customer,
                'message' => 'OTP sent successfully to your phone.',
                'otp' => $otp,  // You may or may not want to send the OTP in the response for security reasons,
                '$netGsmResponse' => $netGsmResponse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No Subscription Available'
            ], 500);
        }
    }

    public function sendSMSThroughXML($phoneNO, $otp, $businessOwnerId)
    {
        $businessOwner = BusinessOwner::find($businessOwnerId);
        $xml = <<<XML
<?xml version="1.0"?>
<mainbody>
   <header>
       <usercode>{$businessOwner->sms_user_code}</usercode>
       <password>{$businessOwner->sms_user_password}</password>
       <msgheader>{$businessOwner->sms_message_header}</msgheader>
       <appkey>{$businessOwner->app_key}</appkey>
   </header>
   <body>
       <msg><![CDATA[Onay Kodu: $otp]]></msg>
       <no>{$phoneNO}</no>
   </body>
</mainbody>
XML;



        Log::info($xml);

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://api.netgsm.com.tr/sms/send/otp'); // The API endpoint
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
        curl_setopt($ch, CURLOPT_POST, true);  // Use POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);  // Send the raw XML data as the body
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/xml',  // Set content type to XML
        ]);

        // Execute the cURL request
        $response = curl_exec($ch);
        Log::info($response);

        // Check for errors
        // Handle cURL error
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);  // Close the cURL session
            return response()->json([
                'success' => false,
                'message' => 'cURL error: ' . $error
            ], 500);  // Return error response
        }

        // Close the cURL session
        curl_close($ch);

        $parsedResponse = simplexml_load_string($response);
        Log::info($parsedResponse);
        if ($parsedResponse) {
            // If the response is valid XML, you can return it to the client
            return response()->json([
                'success' => true,
                'data' => $parsedResponse
            ]);
        } else {
            // If there is an error parsing the response
            return response()->json([
                'success' => false,
                'message' => 'Error parsing XML response from API.'
            ], 500);  // Return error response
        }

    }

    public function verifyOTP(Request $request)
    {
        // Validate required fields
        $request->validate([
            'otp' => 'required', // Assuming OTP is required, adjust validation if needed
            'customerId' => 'required|exists:customer_details,id', // Ensure the customer ID exists in the database
        ]);

        $otp = $request->otp;
        $customerId = $request->customerId;

        // Find customer details
        $customerDetail = CustomerDetail::find($customerId);

        if (!$customerDetail) {
            return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
        }

        // Check if OTP matches
        if ($customerDetail->otp === $otp) {
            $customerDetail->is_verified = true;
            $customerDetail->save();

            //Register TO IYS

            // Fetch the business owner details
            $businessOwner = BusinessOwner::where('id', $customerDetail->business_owner_id)->first();

            if ($businessOwner) {
                // Get coupon codes associated with the business owner's user_id
                $coupon = Coupon::where('user_id', $businessOwner->user_id)
                    ->where('is_default', true)
                    ->first();
                if (!$coupon) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No Coupon Code Available'
                    ], 404);
                }
                $this->registerToIYS($businessOwner, $customerDetail, $coupon);

                // $this->sendCouponCode($businessOwner->id, $customerDetail->phone, $coupon->code);
                $businessOwner->increment('google_reviews');

                return response()->json([
                    'success' => true,
                    'message' => 'OTP verified successfully',
                    'code' => $coupon->code, // Return the coupon codes
                    'couponData' => [
                        'businessOwnerId' => $businessOwner->id,
                        'phoneNo' => $customerDetail->phone,
                        'couponCode' => $coupon->code,
                    ]
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Business owner not found.'
                ], 404);
            }
        }

        // Return error if OTP doesn't match
        return response()->json(['success' => false, 'message' => 'Invalid OTP'], 400);
    }

    public function sendCouponCode(Request $request)
    {
        $businessOwnerId = $request->businessOwnerId;
        $phoneNo = $request->phoneNo;
        $couponCode = $request->couponCode;
        try {
            $businessOwner = BusinessOwner::find($businessOwnerId);
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
               <msg><![CDATA[
                İndirim Kodunuz:\\n{$couponCode}\\n\\nHediyenizi almak için bu kupon kodunu bir garsona gösterin.\\nKod, yalnızca size ait telefon numarasıyla bir kez kullanılabilir.
                ]]></msg>
                <no>{$phoneNo}</no>
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
            Log::info($response);

            // Check for errors
            // Handle cURL error
            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);  // Close the cURL session
                return response()->json([
                    'success' => false,
                    'message' => 'cURL error: ' . $error
                ], 500);  // Return error response
            }

            // Close the cURL session
            curl_close($ch);


            return response()->json([
                'success' => true,
                'data' => []
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

    }


    private function registerToIYS($businessOwner, $customerDetail, $coupon)
    {
        // Prepare the data to send to the IYS API
        $payload = [
            'header' => [
                'username' => $businessOwner->sms_user_code, // Update with correct values
                'password' => $businessOwner->sms_user_password,   // Update with correct values
                'brandCode' => $businessOwner->iys_code,    // Update with correct values
            ],
            'body' => [
                'data' => [
                    [
                        'type' => 'MESAJ',
                        'source' => 'HS_WEB',
                        'recipient' => '+90'.$customerDetail->phone,  // Customer's phone number
                        'status' => 'ONAY',
                        'consentDate' => now()->toDateTimeString(),  // Current time
                        'recipientType' => 'BIREYSEL',
                        'appkey' => $businessOwner->app_key,  // Update with correct appkey
                    ],
                ],
            ],
        ];

        // Send POST request to the IYS API
        $response = Http::post('https://api.netgsm.com.tr/iys/add', $payload); // Replace 'YOUR_API_ENDPOINT' with the actual API endpoint

        // Log or handle the response if needed
        if ($response->successful()) {
            // Optionally log the successful message or do other necessary actions
            Log::info('IYS message sent successfully', $response->json());
        } else {
            // Handle errors (e.g., log or notify)
            Log::error('IYS message failed', $response->json());
        }
    }

}

