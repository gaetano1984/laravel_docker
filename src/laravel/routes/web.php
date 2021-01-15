<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'shop'], function(){
    Route::get('/', 'App\Http\Controllers\ShopController@list')->name('shoplist');
    Route::get('create', 'App\Http\Controllers\ShopController@create')->name('shopCreate');
    Route::get('list', 'App\Http\Controllers\ShopController@list')->name('shopList');
    Route::get('edit/{id}', 'App\Http\Controllers\ShopController@edit')->name('shopEdit');
    Route::get('export', 'App\Http\Controllers\ShopController@export')->name('shopExport');
});