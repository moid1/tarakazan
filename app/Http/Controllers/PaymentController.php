<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function storageCard()
    {
        $options = new \Iyzipay\Options();



        $options->setApiKey("sandbox-dmNi1v7gmMFbuEicQwc4Tps7Dl7Oy9ar");
        $options->setSecretKey("sandbox-Mb0gTEuF0y4u38oC9EBjUeOTHdPA3nQR");
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        $request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId("123456789");
        $request->setPrice("1");
        $request->setPaidPrice("1.2");
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setBasketId("B67832");
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl("http://tarakazan.test/payment-test");
        $request->setEnabledInstallments(array(2, 3, 6, 9));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId("BY789");
        $buyer->setName("John");
        $buyer->setSurname("Doe");
        $buyer->setGsmNumber("+905350000000");
        $buyer->setEmail("email@email.com");
        $buyer->setIdentityNumber("74300864791");
        $buyer->setLastLoginDate("2015-10-05 12:43:35");
        $buyer->setRegistrationDate("2013-04-21 15:12:09");
        $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $buyer->setIp("85.34.78.112");
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34732");
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName("Jane Doe");
        $shippingAddress->setCity("Istanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $shippingAddress->setZipCode("34742");
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName("Jane Doe");
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $billingAddress->setZipCode("34742");
        $request->setBillingAddress($billingAddress);

        $basketItems = array();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("BI101");
        $firstBasketItem->setName("Binocular");
        $firstBasketItem->setCategory1("Collectibles");
        $firstBasketItem->setCategory2("Accessories");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $firstBasketItem->setPrice("0.3");
        $basketItems[0] = $firstBasketItem;

        $secondBasketItem = new \Iyzipay\Model\BasketItem();
        $secondBasketItem->setId("BI102");
        $secondBasketItem->setName("Game code");
        $secondBasketItem->setCategory1("Game");
        $secondBasketItem->setCategory2("Online Game Items");
        $secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $secondBasketItem->setPrice("0.5");
        $basketItems[1] = $secondBasketItem;

        $thirdBasketItem = new \Iyzipay\Model\BasketItem();
        $thirdBasketItem->setId("BI103");
        $thirdBasketItem->setName("Usb");
        $thirdBasketItem->setCategory1("Electronics");
        $thirdBasketItem->setCategory2("Usb / Cable");
        $thirdBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $thirdBasketItem->setPrice("0.2");
        $basketItems[2] = $thirdBasketItem;
        $request->setBasketItems($basketItems);

        # make request
        $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($request, $options);
        dd($payWithIyzicoInitialize);

        #verify signature
        $conversationId = $payWithIyzicoInitialize->getConversationId();
        $token = $payWithIyzicoInitialize->getToken();
        $signature = $payWithIyzicoInitialize->getSignature();
        $calculatedSignature = calculateHmacSHA256Signature(array($conversationId, $token));
        $verified = $signature == $calculatedSignature;
        echo "Signature verified: $verified";


    }

    public function test(Request $request)
    {
        
        echo 'OK';
        \Log::info($request->all());


       
        $subscription = Subscription::where('token', $request->hash)->first();
        if ($subscription) {
            $subscription->start_date = now();
            $subscription->end_date = now()->addMonth();
            $subscription->status = 'active';
            $subscription->save();

            $user = User::find($subscription->user_id);
            if ($user) {
                $user->is_paid = true;
                $user->save();
            }
            
            $busessOwenr = BusinessOwner::where('user_id', $subscription->user_id)->first();
            if($busessOwenr){
                $busessOwenr->update(['package'=>$subscription->package_id]);
            }

        }
        return redirect()->route('home')->with('success','Your subscription has been successfully activated!');

    }
}
