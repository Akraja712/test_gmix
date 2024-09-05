<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('userdetails', [AuthController::class, 'userdetails']);
Route::post('product_list', [AuthController::class, 'product_list']);
Route::post('add_address', [AuthController::class, 'add_address']);
Route::post('address_list', [AuthController::class, 'address_list']);
Route::post('place_order', [AuthController::class, 'place_order']);
Route::post('orders_list', [AuthController::class, 'orders_list']);
Route::post('otp', [AuthController::class, 'otp']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
