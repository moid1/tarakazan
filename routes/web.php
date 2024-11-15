<?php

use App\Http\Controllers\BusinessOwnerController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SocialMediaController;
use Illuminate\Support\Facades\Route;

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

// Admin packages routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('packages/{package}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
});



//ChatBot
Route::get('/chatbot/{slug}', [ChatBotController::class, 'show'])->name('chatbot.show');
Route::post('/chatbot/{slug}/store', [ChatbotController::class, 'store'])->name('chatbot.store');


// Business Owners
Route::get('social-media-insights', [SocialMediaController::class, 'index'])->name('social-media.index');
Route::post('/update-social-interactions', [SocialMediaController::class, 'updateSocialMediaCount']);


//Business-Owners Gifts
Route::get('coupon-management', [CouponController::class, 'index'])->name('coupon.index');
Route::get('/coupon-create', [CouponController::class, 'create'])->name('coupon.create');
Route::post('/coupon-store', [CouponController::class, 'store'])->name('coupon.store');

// Route to display the business owner's profile
Route::get('/profile', [BusinessOwnerController::class, 'showProfile'])->name('business-owner.profile');
// Route to update the business owner's profile
Route::post('/profile/update', [BusinessOwnerController::class, 'updateProfile'])->name('business-owner.update-profile');


// Business-Owners Campaigns
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaign.index');
Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaign.create');
Route::post('/campaigns/store', [CampaignController::class, 'store'])->name('campaign.store');

//Business-Owners Customers
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('customers/export-pdf', [CustomerController::class, 'exportPdf'])->name('admin.customer.pdf.export');


Route::post('/save-customer-data', [ChatbotController::class, 'saveCustomerData']);
