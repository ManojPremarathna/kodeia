<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductContoller;

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

Route::get('/', [ProductContoller::class, 'index']);
Route::get('/sync-products', [ProductContoller::class, 'syncProducts'])->name('sync.products');
