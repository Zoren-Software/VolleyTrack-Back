<?php

use Illuminate\Support\Facades\Route;
use Laravel\Horizon\Horizon;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginController;

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

    /* Route::group(['middleware' => 'auth'], function () {
        Horizon::auth(function ($request) {
            return true;
        });
    }); */
 
    Route::get('/auth/github/redirect', [LoginController::class, 'gitHubRedirect'])->name('github.login');
    
    Route::get('/auth/github/callback', [LoginController::class, 'gitHubCallback'])->name('github.callback');
});
