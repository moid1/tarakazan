<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Package;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function create()
    {
        $businessOwner = BusinessOwner::where('user_id', Auth::id())->first();
        $isAlreadyHaveSubscription = Subscription::where([['user_id', auth()->user()->id], ['status', 'active']])->exists();
        if ($isAlreadyHaveSubscription) {
            return back()->with('success', 'You have already subscription');
        }
        $package = Package::find($businessOwner->package);
        $user = auth()->user();

        $merchant_id = '528114';
        $merchant_key = 'UpCe6QKAd5Uij91h';
        $merchant_salt = 'bqAnpBwgMs8qPFAb';

        $email = $user->email;
        $payment_amount = floatval($package->price) * 100;
        $merchant_oid = rand(100000, 999999);
        $user_name = $user->name;
        $merchant_ok_url = "http://panel.tarakazan.com.tr/home";
        $merchant_fail_url = "http://panel.tarakazan.com.tr/home";
        $user_basket = "";
        #
        // EXAMPLE $user_basket creation - You can duplicate arrays per each product
        $user_basket = base64_encode(json_encode(array(
            array($package->name, $package->price, 1),
        )));


        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $user_ip = $ip;
        $timeout_limit = "30";
        $debug_on = 1;
        $test_mode = 0;
        $no_installment = 0;
        $max_installment = 0;
        $currency = "TL";

        $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
        $post_vals = array(
            'merchant_id' => $merchant_id,
            'user_ip' => $user_ip,
            'merchant_oid' => $merchant_oid,
            'email' => $email,
            'payment_amount' => $payment_amount,
            'paytr_token' => $paytr_token,
            'user_basket' => $user_basket,
            'debug_on' => $debug_on,
            'no_installment' => $no_installment,
            'max_installment' => 0,
            'user_name' => $user_name,
            'user_address' => 'test',
            'user_phone' => 'test',
            'merchant_ok_url' => $merchant_ok_url,
            'merchant_fail_url' => $merchant_fail_url,
            'timeout_limit' => $timeout_limit,
            'currency' => $currency,
            'test_mode' => $test_mode
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        //XXX: DİKKAT: lokal makinanızda "SSL certificate problem: unable to get local issuer certificate" uyarısı alırsanız eğer
        //aşağıdaki kodu açıp deneyebilirsiniz. ANCAK, güvenlik nedeniyle sunucunuzda (gerçek ortamınızda) bu kodun kapalı kalması çok önemlidir!
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = @curl_exec($ch);

        if (curl_errno($ch))
            die("PAYTR IFRAME connection error. err:" . curl_error($ch));

        curl_close($ch);

        $result = json_decode($result, 1);

        if ($result['status'] == 'success')
            $token = $result['token'];
        else
            die("PAYTR IFRAME failed. reason:" . $result['reason']);

        Subscription::create([
            'user_id' => Auth::id(),
            'package_id' => $package->id,
            'status' => 'inactive',
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Adds 1 month to the current date
            'token' => $merchant_oid
        ]);


        return view('business_owners.subscriptions.create', compact('package', 'token'));













    }

    public function store(Request $request)
    {
        dd($request->all());
        $this->chargePayment($request->all());
    }



    private function chargePayment($requestData)
    {
        $user = auth()->user();

        $businessOwner = BusinessOwner::where('user_id', Auth::id())->first();
        $package = Package::find($businessOwner->package);
        $options = new \Iyzipay\Options();
        $options->setApiKey("sandbox-dmNi1v7gmMFbuEicQwc4Tps7Dl7Oy9ar");
        $options->setSecretKey("sandbox-Mb0gTEuF0y4u38oC9EBjUeOTHdPA3nQR");
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        $request = new \Iyzipay\Request\CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId("123456789");
        $request->setPrice($package->price);
        $request->setPaidPrice($package->price);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setInstallment(1);

        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($requestData->card_holder_name);
        $paymentCard->setCardNumber($requestData->card_number);
        $paymentCard->setExpireMonth($requestData->expiry_month);
        $paymentCard->setExpireYear($requestData->expiry_year);
        $paymentCard->setCvc($requestData->cvc);
        $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($user->id);
        $buyer->setName($user->name);
        $buyer->setEmail(auth()->user()->email);
        $buyer->setIdentityNumber($user->id);
        $buyer->setRegistrationAddress($businessOwner->address);
        $buyer->setCity("Istanbul");
        $buyer->setCountry($businessOwner->country);
        // $buyer->setZipCode("34732");
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($user->name);
        $shippingAddress->setCity("Istanbul");
        $shippingAddress->setCountry($businessOwner->country);
        $shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $request->setShippingAddress($shippingAddress);


    }


    public function allSubscriptions()
    {
        $subscriptions = Subscription::all();
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function getSoonExpireBusinessOwners()
    {
        $soonExpireOrExpired = Subscription::where(function ($query) {
            // Get the records where end_date is either within 7 days or has passed.
            $query->where('end_date', '<=', Carbon::now()->addDays(7)->toDateString())
                ->where('end_date', '>=', Carbon::now()->toDateString()); // Active subscription check
        })
            ->orWhere('end_date', '<', Carbon::now()->toDateString()) // Expired subscriptions
            ->get();
        return view('admin.subscriptions.expire', compact('soonExpireOrExpired'));

    }

    public function upgradeSMSPackage()
    {
        // Get the business owner for the authenticated user
        $businessOwner = BusinessOwner::where('user_id', Auth::id())->first();
        $user = auth()->user();

        // Check if the user already has an active subscription
        $isAlreadyHaveSubscription = Subscription::where([['user_id', auth()->user()->id], ['status', 'active']])->exists();
        if ($isAlreadyHaveSubscription) {
            return back()->with('success', 'You already have an active subscription.');
        }

        // Get the current package
        $currentPackage = Package::find($businessOwner->package);

        // Find the next package (order by id or any other criteria)
        $nextPackage = Package::where('id', '>', $currentPackage->id)->orderBy('id')->first();

        // Check if a next package exists
        if (!$nextPackage) {
            return back()->with('error', 'No higher package available for upgrade.');
        }

        $merchant_id = '528114';
        $merchant_key = 'UpCe6QKAd5Uij91h';
        $merchant_salt = 'bqAnpBwgMs8qPFAb';

        $email = $user->email;
        $payment_amount = floatval($nextPackage->price)*100;
        $merchant_oid = rand(100000, 999999);
        $user_name = $user->name;
        $merchant_ok_url = "http://panel.tarakazan.com.tr/home";
        $merchant_fail_url = "http://panel.tarakazan.com.tr/home";
        $user_basket = "";
        #
        // EXAMPLE $user_basket creation - You can duplicate arrays per each product
        $user_basket = base64_encode(json_encode(array(
            array($nextPackage->name, $nextPackage->price, 1),
        )));


        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        $user_ip = $ip;
        $timeout_limit = "30";
        $debug_on = 1;
        $test_mode = 0;
        $no_installment = 0;
        $max_installment = 0;
        $currency = "TL";

        $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
        $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
        $post_vals = array(
            'merchant_id' => $merchant_id,
            'user_ip' => $user_ip,
            'merchant_oid' => $merchant_oid,
            'email' => $email,
            'payment_amount' =>$payment_amount,
            'paytr_token' => $paytr_token,
            'user_basket' => $user_basket,
            'debug_on' => $debug_on,
            'no_installment' => $no_installment,
            'max_installment' => 0,
            'user_name' => $user_name,
            'user_address' => 'test',
            'user_phone' => 'test',
            'merchant_ok_url' => $merchant_ok_url,
            'merchant_fail_url' => $merchant_fail_url,
            'timeout_limit' => $timeout_limit,
            'currency' => $currency,
            'test_mode' => $test_mode
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        //XXX: DİKKAT: lokal makinanızda "SSL certificate problem: unable to get local issuer certificate" uyarısı alırsanız eğer
        //aşağıdaki kodu açıp deneyebilirsiniz. ANCAK, güvenlik nedeniyle sunucunuzda (gerçek ortamınızda) bu kodun kapalı kalması çok önemlidir!
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = @curl_exec($ch);

        if (curl_errno($ch))
            die("PAYTR IFRAME connection error. err:" . curl_error($ch));

        curl_close($ch);

        $result = json_decode($result, 1);

        if ($result['status'] == 'success')
            $token = $result['token'];
        else
            die("PAYTR IFRAME failed. reason:" . $result['reason']);

        Subscription::create([
            'user_id' => Auth::id(),
            'package_id' => $nextPackage->id,
            'status' => 'inactive',
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Adds 1 month to the current date
            'token' => $merchant_oid
        ]);

        // Redirect the user to the payment page
        return view('business_owners.subscriptions.create', compact('nextPackage', 'token'));
    }

}
