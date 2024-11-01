<?php

use App\Http\Controllers\BusinessOwnerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Admin -- Business Owners
Route::get('business-owners/create', [BusinessOwnerController::class, 'create'])->name('admin.business.owner.create');
Route::post('/business-owner/store', [BusinessOwnerController::class, 'store'])->name('admin.business.owner.store');
