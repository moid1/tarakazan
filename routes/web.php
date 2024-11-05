<?php

use App\Http\Controllers\BusinessOwnerController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SocialMediaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Admin -- Business Owners
Route::get('business-owners/create', [BusinessOwnerController::class, 'create'])->name('admin.business.owner.create');
Route::get('/business-owners', [BusinessOwnerController::class, 'index'])->name('admin.business.owner.index');
Route::post('/business-owner/store', [BusinessOwnerController::class, 'store'])->name('admin.business.owner.store');


//Admin -- Packages
Route::get('admin/packages', [PackageController::class, 'index'])->name('admin.packages.index');
Route::get('admin/packages/create', [PackageController::class, 'create'])->name('admin.packages.create');
Route::post('admin/packages', [PackageController::class, 'store'])->name('admin.packages.store');


//ChatBot
Route::get('/chatbot/{slug}', [ChatBotController::class, 'show'])->name('chatbot.show');
Route::post('/chatbot/{slug}/store', [ChatbotController::class, 'store'])->name('chatbot.store');


// Business Owners
Route::get('social-media-insights', [SocialMediaController::class, 'index'])->name('social-media.index');

//Business-Owners Gifts
Route::get('coupon-management', [CouponController::class, 'index'])->name('coupon.index');
Route::get('/coupon-create', [CouponController::class,'create'])->name('coupon.create');