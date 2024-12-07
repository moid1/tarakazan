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

        // return view('business_owners.subscriptions.create', compact('package'));

        $options = new \Iyzipay\Options();
        $options->setApiKey("sandbox-dmNi1v7gmMFbuEicQwc4Tps7Dl7Oy9ar");
        $options->setSecretKey("sandbox-Mb0gTEuF0y4u38oC9EBjUeOTHdPA3nQR");
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId("123456789");
        $request->setPrice($package->price);
        $request->setPaidPrice($package->price);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        // $request->setBasketId("B67832");
        // $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl("http://tarakazan.test/payment-test");
        // $request->setEnabledInstallments(array(2, 3, 6, 9));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($user->id);
        $buyer->setName($user->name);
        $buyer->setSurname($user->name);
        $buyer->setEmail(auth()->user()->email);
        $buyer->setIdentityNumber('000000000' . $user->id);
        $buyer->setRegistrationAddress($businessOwner->address);
        $buyer->setCity("Istanbul");
        $buyer->setCountry($businessOwner->country);
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($user->name);
        $shippingAddress->setCity("Istanbul");
        $shippingAddress->setCountry($businessOwner->country);
        $shippingAddress->setAddress($businessOwner->address);
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($user->name);
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry($businessOwner->country);
        $billingAddress->setAddress($businessOwner->address);
        $request->setBillingAddress($billingAddress);

        $basketItems = array();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("BI101");
        $firstBasketItem->setName($package->name);
        $firstBasketItem->setCategory1($package->name);
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $firstBasketItem->setPrice($package->price);
        $basketItems[0] = $firstBasketItem;


        $request->setBasketItems($basketItems);

        # make request
        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

        $token = $checkoutFormInitialize->getToken();
        // dd($checkoutFormInitialize['paymentPageUrl']);
        $result = $checkoutFormInitialize->getPaymentPageUrl(); // Example method

        Subscription::create([
            'user_id' => Auth::id(),
            'package_id' => $package->id,
            'status' => 'inactive',
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Adds 1 month to the current date
            'token' => $token
        ]);
        return redirect()->to($result);







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

        // Prepare payment options (for Iyzipay integration)
        $options = new \Iyzipay\Options();
        $options->setApiKey("sandbox-dmNi1v7gmMFbuEicQwc4Tps7Dl7Oy9ar");
        $options->setSecretKey("sandbox-Mb0gTEuF0y4u38oC9EBjUeOTHdPA3nQR");
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        // Create a new checkout request
        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId("123456789");
        $request->setPrice($nextPackage->price);
        $request->setPaidPrice($nextPackage->price);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setCallbackUrl("http://tarakazan.test/payment-test");

        // Buyer and address details
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(auth()->user()->id);
        $buyer->setName(auth()->user()->name);
        $buyer->setSurname(auth()->user()->name);
        $buyer->setEmail(auth()->user()->email);
        $buyer->setIdentityNumber('000000000' . auth()->user()->id);
        $buyer->setRegistrationAddress($businessOwner->address);
        $buyer->setCity("Istanbul");
        $buyer->setCountry($businessOwner->country);
        $request->setBuyer($buyer);

        // Shipping and billing address (same for both)
        $address = new \Iyzipay\Model\Address();
        $address->setContactName(auth()->user()->name);
        $address->setCity("Istanbul");
        $address->setCountry($businessOwner->country);
        $address->setAddress($businessOwner->address);
        $request->setShippingAddress($address);
        $request->setBillingAddress($address);

        // Basket item (the package being upgraded to)
        $basketItems = array();
        $basketItem = new \Iyzipay\Model\BasketItem();
        $basketItem->setId("BI101");
        $basketItem->setName($nextPackage->name);
        $basketItem->setCategory1($nextPackage->name);
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $basketItem->setPrice($nextPackage->price);
        $basketItems[0] = $basketItem;
        $request->setBasketItems($basketItems);

        // Make the request to Iyzipay API
        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);
        $token = $checkoutFormInitialize->getToken();
        $paymentPageUrl = $checkoutFormInitialize->getPaymentPageUrl();

        // Create the new subscription
        Subscription::create([
            'user_id' => Auth::id(),
            'package_id' => $nextPackage->id,
            'status' => 'inactive', // Initially inactive until payment is completed
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Set end date for 1 month from now
            'token' => $token
        ]);

        // Redirect the user to the payment page
        return redirect()->to($paymentPageUrl);
    }

}
