<?php

use App\Http\Controllers\BusinessOwnerCampaignSMSController;
use App\Http\Controllers\BusinessOwnerController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;

Route::get('migrate', function () {
    \Artisan::call('migrate');
    dd("Migration done");
});

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Admin -- Business Owners
Route::get('business-owners/create', [BusinessOwnerController::class, 'create'])->name('admin.business.owner.create');
Route::get('business-owners', [BusinessOwnerController::class, 'index'])->name('admin.business.owner.index');
Route::post('business-owner/store', [BusinessOwnerController::class, 'store'])->name('admin.business.owner.store');
Route::get('business-owners/{businessOwner}/edit', [BusinessOwnerController::class, 'edit'])->name('admin.business.owner.edit'); // Edit route
Route::put('business-owners/{businessOwner}', [BusinessOwnerController::class, 'update'])->name('admin.business.owner.update');
Route::delete('business-owners/{businessOwner}', [BusinessOwnerController::class, 'destroy'])->name('admin.business.owner.destroy');
Route::get('admin/business-owners/export-pdf', [BusinessOwnerController::class, 'exportPdf'])->name('admin.business.owner.exportPdf');
Route::get('admin/business-owners/export-csv', [BusinessOwnerController::class, 'exportCsv'])->name('admin.business.owner.exportCsv');


Route::delete('/delete-customer/{id}', [HomeController::class, 'deleteCustomer'])->name('admin.customer.destroy');

// Admin packages routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('packages/{package}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');

    Route::get('all-subscriptions', [SubscriptionController::class, 'allSubscriptions'])->name('subscription.all');
    Route::get('/soon-expire', [SubscriptionController::class, 'getSoonExpireBusinessOwners'])->name('soon.expire');
});



//ChatBot
Route::get('/qrcode/{slug}', [ChatBotController::class, 'getNewChatBot'])->name('chatbot.show');
Route::get('/bot/{slug}', [ChatBotController::class, 'getNewChatBot'])->name('chatbot.new.show');
Route::post('/chatbot/{slug}/store', [ChatbotController::class, 'store'])->name('chatbot.store');


// Business Owners
Route::get('social-media-insights', [SocialMediaController::class, 'index'])->name('social-media.index');
Route::post('/update-social-interactions', [SocialMediaController::class, 'updateSocialMediaCount']);


//Business-Owners Gifts
Route::get('coupon-management', [CouponController::class, 'index'])->name('coupon.index');
Route::get('/coupon-create', [CouponController::class, 'create'])->name('coupon.create');
Route::post('/coupon-store', [CouponController::class, 'store'])->name('coupon.store');
Route::get('/coupon-default/{id}',[CouponController::class,'makeDefault'])->name('coupon.default');
Route::get('/coupon/delete/{id}',[CouponController::class, 'destroy'])->name('coupon.delete');

// Route to display the business owner's profile
Route::get('/profile', [BusinessOwnerController::class, 'showProfile'])->name('business-owner.profile');
// Route to update the business owner's profile
Route::post('/profile/update', [BusinessOwnerController::class, 'updateProfile'])->name('business-owner.update-profile');


// Business-Owners Campaigns
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaign.index');
Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaign.create');
Route::post('/campaigns/store', [CampaignController::class, 'store'])->name('campaign.store');
Route::get('/campaign/{id}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');
Route::put('/campaign/{id}', [CampaignController::class, 'update'])->name('campaign.update');
Route::delete('/campaign/{id}', [CampaignController::class, 'destroy'])->name('campaign.destroy');

// Business Owners SMS
Route::get('/send-campaign-sms/create', [BusinessOwnerCampaignSMSController::class, 'create'])->name('campaign.sms.create');
Route::get('/send-campaign-sms', [BusinessOwnerCampaignSMSController::class, 'index'])->name('campaign.sms.index');
Route::get('/send-campaign-sms/edit/{id}', [BusinessOwnerCampaignSMSController::class, 'edit'])->name('campaign.sms.edit');
Route::post('/send-sms-campaign', [BusinessOwnerCampaignSMSController::class, 'store'])->name('campaign.sms.store');
Route::put('/send-campaign-sms/{id}', [BusinessOwnerCampaignSMSController::class, 'update'])->name('campaign.sms.update');
Route::delete('/send-campaign-sms/{id}', [BusinessOwnerCampaignSMSController::class, 'destroy'])->name('campaign.sms.destroy');

//Business-Owners Customers
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('customers/export-pdf', [CustomerController::class, 'exportPdf'])->name('admin.customer.pdf.export');

//Busienss-Owners Waiter
Route::post('/waiter/store', [WaiterController::class, 'store'])->name('waiter.store');
Route::get('/waiter/create', [WaiterController::class, 'create'])->name('waiter.create');
Route::get('/waiter', [WaiterController::class, 'index'])->name('waiter.index');
Route::delete('/waiter/{id}', [WaiterController::class, 'destroy'])->name('waiter.destroy');

Route::post('/save-customer-data', [ChatbotController::class, 'saveCustomerData']);

//send OTP SMS 
Route::post('send-otp-sms', [SMSController::class, 'sendOTPSMSToCustomer']);
Route::post('verify-otp', [SMSController::class, 'verifyOTP']);


//
Route::get('payment', [PaymentController::class, 'storageeCard']);
Route::post('payment-test', [PaymentController::class, 'test']);


// SUBSCRIPTION FOR BUSINESS OWNERS
Route::get('/subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
Route::get('/upgrade-subscription', [SubscriptionController::class, 'upgradeSMSPackage'])->name('upgrade.sms.package');

Route::get('/block-user', [CustomerController::class, 'toggleBlockUser'])->name('admin.block.user');

Route::get('lang', [LanguageController::class, 'change'])->name("change.lang");


// Waiter Verify Code

Route::post('/verify-coupon-code', [WaiterController::class, 'verifyCouponCode'])->name('waiter.verify.code');
Route::post('/remove-from-blacklist', [WaiterController::class, 'removeFromBlackList'])->name('waiter.remove.blacklist');

Route::post('/send-coupon-code',[SMSController::class, 'sendCouponCode']);

Route::view('/custom-payment', 'payment.iframe');


