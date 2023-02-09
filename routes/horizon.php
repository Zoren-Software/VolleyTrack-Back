<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
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

Route::get('/', function () {
    return view('welcome-horizon');
});

Route::group(['middleware' => 'auth'], function () {
    Horizon::auth(function ($request) {
        return auth()->check();
    });
});

Route::get('/auth/github/redirect', [LoginController::class, 'githubRedirect'])->name('github.login');

Route::get('/auth/github/callback', [LoginController::class, 'githubCallback']);
