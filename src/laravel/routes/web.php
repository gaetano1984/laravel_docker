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
    Route::get('/', [App\Http\Controllers\ShopController::class, ''])->name('shoplist');
    Route::get('create', [App\Http\Controllers\ShopController::class, 'create'])->name('shopCreate');
    Route::get('list', [App\Http\Controllers\ShopController::class, 'list'])->name('shopList');
    Route::get('edit/{id}', [App\Http\Controllers\ShopController::class, 'edit'])->name('shopEdit');
    Route::get('export', [App\Http\Controllers\ShopController::class, 'export'])->name('shopExport');
});