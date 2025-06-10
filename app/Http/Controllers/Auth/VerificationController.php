<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;

class VerificationController extends Controller
{
    /**
     * @codeCoverageIgnore
     *
     * @param  string|null  $tenant
     * @param  string|null  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($tenant = null, $token = null)
    {
        $tenant = Tenant::where('id', $tenant)->first();

        if (!$tenant) {
            throw new \Exception('Tenant não encontrado');
        }

        tenancy()->initialize($tenant->id);

        $user = User::where('set_password_token', $token)->first();

        if (!$user) {
            throw new \Exception('Token inválido ou usuário não encontrado');
        }

        $user->email_verified_at = now();
        $user->save();

        $protocol = config('app.protocol');
        $domain = config('app.external_tenant_url');

        if (!is_string($protocol) || !is_string($domain)) {
            throw new \RuntimeException('Configurações de URL inválidas');
        }

        $subdomain = (string) $tenant->id;
        $email = (string) $user->email;
        $token = (string) $token;

        $link = "{$protocol}://{$subdomain}.{$domain}/set-password/{$email}/{$token}";

        return redirect()->away($link);
    }
}
