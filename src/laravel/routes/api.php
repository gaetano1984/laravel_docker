<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'shop'], function(){
    Route::post('create', [App\Http\Controllers\ShopController::class, 'shopCreate'])->name('apiShopCreate');
    Route::post('list', [App\Http\Controllers\ShopController::class, 'shopList'])->name('apiShopList');
    Route::post('update', [App\Http\Controllers\ShopController::class, 'shopUpdate'])->name('apiShopUpdate');
});