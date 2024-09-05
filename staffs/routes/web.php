<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Controllers\CartController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\AddressesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\StaffsController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;    
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AppsettingsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\VerificationsController;
use App\Http\Controllers\FakechatsController;
use App\Http\Controllers\Chat_pointsController;
use App\Http\Controllers\FeedbacksController;
use App\Http\Controllers\Recharge_transController;
use App\Http\Controllers\Verification_transController;
use App\Http\Controllers\ProfessionsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\BulkUserController;
use App\Models\UserNotifications;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/');
});





Route::namespace('Auth')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'RegisterController@register');

});

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::resource('customers', CustomerController::class);

  
    Route::get('/staffs', [StaffsController::class, 'index'])->name('staffs.index');
    Route::get('/staffs/staffs', [StaffsController::class, 'create'])->name('staffs.create');
    Route::get('/staffs/{staff}/edit', [StaffsController::class, 'edit'])->name('staffs.edit');
    Route::delete('/staffs/{staffs}', [StaffsController::class, 'destroy'])->name('staffs.destroy');
    Route::put('/staffs/{staff}', [StaffsController::class, 'update'])->name('staffs.update');
    Route::post('/staffs', [StaffsController::class, 'store'])->name('staffs.store');

    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/orders', [OrdersController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
    Route::get('/user-addresses/{userId}', [OrdersController::class, 'getUserAddresses']);


// OneSignal service worker route
Route::get('/OneSignalSDKWorker.js', function () {
    return response()->file(public_path('OneSignalSDKWorker.js'));
});