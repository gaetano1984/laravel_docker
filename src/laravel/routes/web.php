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

Route::get('register', 'App\Http\Controllers\AuthController@register')->name('register');
Route::post('register', 'App\Http\Controllers\AuthController@postRegister')->name('postRegister');
Route::get('login', 'App\Http\Controllers\AuthController@login')->name('login');
Route::post('login', 'App\Http\Controllers\AuthController@postLogin')->name('postLogin');
Route::group(['middleware' => 'auth'], function(){
	Route::group(['prefix' => 'news'], function(){
        Route::get('/', 'App\Http\Controllers\NewsController@index')->name('home');
        Route::post('/', 'App\Http\Controllers\NewsController@index')->name('homeFiltered');
		Route::get('favorite', 'App\Http\Controllers\NewsController@favorite')->name('favorite');
		Route::post('favorite', 'App\Http\Controllers\NewsController@postFavorite')->name('postFavorite');
	});
});