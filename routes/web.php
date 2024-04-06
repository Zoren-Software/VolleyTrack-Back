<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
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
})->name('welcome');

// Password reset link request routes...
Route::get('password/email', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.email');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);

// Password reset routes...
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.request');
Route::post('password/reset', [ResetPasswordController::class, 'postReset'])->name('password.reset');

Route::get('verify-email/{tenant}/{token}', [VerificationController::class, 'verify'])->name('verify.email');
Route::get('set-password/{tenant}/{token}', [PasswordResetController::class, 'showSetPasswordForm'])->name('password.reset');
Route::post('set-password/{tenant}/{token}', [PasswordResetController::class, 'setPassword'])->name('password.set');

Route::get('/test-notification-training-mail', function () {
    $training = App\Models\Training::find(1);
    $user = App\Models\User::find(1);

    return new App\Mail\Training\NotificationTrainingMail($training, $user);
});

Route::get('/test-confirmation-notification-training-mail', function () {
    tenancy()->initialize('test');

    $training = App\Models\Training::find(1);
    $user = App\Models\User::find(3);

    return new App\Mail\Training\ConfirmationNotificationTrainingMail($training, $user);
});

Route::get('/test-cancellation-notification-training-mail', function () {
    tenancy()->initialize('test');

    $training = App\Models\Training::find(1);
    $user = App\Models\User::find(3);

    return new App\Mail\Training\CancellationNotificationTrainingMail($training, $user);
});

Route::get('/test-confirm-email-and-create-password', function () {

    tenancy()->initialize('test');

    $user = App\Models\User::find(3);

    return new App\Mail\User\ConfirmEmailAndCreatePasswordMail($user, tenant('id'));
});
