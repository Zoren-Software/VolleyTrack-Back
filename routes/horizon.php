<?php

use Illuminate\Support\Facades\Route;
use Laravel\Horizon\Horizon;


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

Route::group(['domain' => 'horizon.' . appHost()], function () {
    Route::get('/', function () {
        return view('welcome-horizon');
    });

    Route::group(['middleware' => 'auth'], function () {
        Horizon::auth(function ($request) {
            return true;
        });
    });
});
