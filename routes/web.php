<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
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

// NOTE - Rotas de teste de template de e-mail
if (app()->environment('local') && config('app.debug')) {
    Route::get('/test-notification-training-mail', function () {
        tenancy()->initialize('test');

        $training = App\Models\Training::find(1);
        $user = App\Models\User::find(1);

        return new App\Mail\Training\TrainingMail($training, $user);
    });

    Route::get('/test-confirmation-notification-training-mail', function () {
        tenancy()->initialize('test');

        $training = App\Models\Training::find(1);
        if (!$training) {
            $team = App\Models\Team::factory()
                ->hasPlayers(10)
                ->create();

            $training = App\Models\Training::factory()
                ->setTeamId($team->id)
                ->setStatus(true)
                ->create();
        }

        $user = App\Models\User::find(3);
        if (!$user) {
            $user = App\Models\User::factory()->create();
        }

        return new App\Mail\Training\ConfirmationTrainingMail($training, $user);
    });

    Route::get('/test-cancellation-notification-training-mail', function () {
        tenancy()->initialize('test');

        $training = App\Models\Training::find(1);
        $user = App\Models\User::find(3);

        return new App\Mail\Training\CancellationTrainingMail($training, $user);
    });

    Route::get('/test-confirm-email-and-create-password', function () {
        tenancy()->initialize('test');

        $user = App\Models\User::find(3);

        return new App\Mail\User\ConfirmEmailAndCreatePasswordMail($user, tenant('id'), true);
    });

    Route::get('/test-forgot-password', function () {
        tenancy()->initialize('test');

        $user = App\Models\User::find(3);

        return new App\Mail\User\ForgotPasswordMail($user, tenant('id'));
    });
}
