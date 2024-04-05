<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;

class VerificationController extends Controller
{
    /**
     * @codeCoverageIgnore
     *
     * @param  null  $token
     * @return [type]
     */
    public function verify($tenant = null, $token = null)
    {
        $tenant = Tenant::where('id', $tenant)->first();

        if (!$tenant) {
            throw new \Exception('Tenant não encontrado');
        }

        tenancy()->initialize($tenant->id);

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            throw new \Exception('Token inválido ou usuário não encontrado');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('password.reset', ['token' => $token, 'tenant' => $tenant->id]);
    }

}
