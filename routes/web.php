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
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AppsettingsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RewardproductsController;
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
    return redirect('/admin');
});

Auth::routes();



Route::namespace('Auth')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'RegisterController@register');

    Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset', 'ResetPasswordController@reset');
});
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::resource('customers', CustomerController::class);


    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::get('/users/{users}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::delete('/users/{users}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{users}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');


      //User
      Route::get('/addresses', [AddressesController::class, 'index'])->name('addresses.index');
      Route::get('/addresses/create', [AddressesController::class, 'create'])->name('addresses.create');
      Route::get('/addresses/{addresses}/edit', [AddressesController::class, 'edit'])->name('addresses.edit');
      Route::delete('/addresses/{addresses}', [AddressesController::class, 'destroy'])->name('addresses.destroy');
      Route::put('/addresses/{addresses}', [AddressesController::class, 'update'])->name('addresses.update');
      Route::post('/addresses', [AddressesController::class, 'store'])->name('addresses.store');


        //User
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/products', [ProductsController::class, 'create'])->name('products.create');
    Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit');
    Route::delete('/products/{products}', [ProductsController::class, 'destroy'])->name('products.destroy');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');

            //reward_points
            Route::get('/reward_products', [RewardproductsController::class, 'index'])->name('reward_products.index');
            Route::get('/reward_products/reward_products', [RewardproductsController::class, 'create'])->name('reward_products.create');
            Route::get('/reward_products/{reward_products}/edit', [RewardproductsController::class, 'edit'])->name('reward_products.edit');
            Route::delete('/reward_products/{reward_products}', [RewardproductsController::class, 'destroy'])->name('reward_products.destroy');
            Route::put('/reward_products/{reward_products}', [RewardproductsController::class, 'update'])->name('reward_products.update');
            Route::post('/reward_products', [RewardproductsController::class, 'store'])->name('reward_products.store');

    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::delete('/orders/{orders}', [OrdersController::class, 'destroy'])->name('orders.destroy');
    });

    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/orders', [OrdersController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
    Route::get('/user-addresses/{userId}', [OrdersController::class, 'getUserAddresses']);
    Route::post('/orders/verify', [OrdersController::class, 'verify'])->name('orders.verify');
    Route::get('/orders/{order}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrdersController::class, 'update'])->name('orders.update');

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::resource('customers', CustomerController::class);

    Route::get('news/1/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::post('news/1/update', [NewsController::class, 'update'])->name('news.update');

        Route::get('appsettings/{id}/edit', [AppsettingsController::class, 'edit'])->name('appsettings.edit');
        Route::put('appsettings/{id}/update', [AppsettingsController::class, 'update'])->name('appsettings.update');
        


// OneSignal service worker route
Route::get('/OneSignalSDKWorker.js', function () {
    return response()->file(public_path('OneSignalSDKWorker.js'));
});