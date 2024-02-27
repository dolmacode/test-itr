<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['controller' => PageController::class], function () {
    Route::get('/', 'index')->name('page.index');
});

Route::group(['prefix' => 'products', 'controller' => ProductController::class], function () {
    Route::post('upload', 'upload')->name('products.upload');
});
