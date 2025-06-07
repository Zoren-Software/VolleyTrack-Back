<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function showSetPasswordForm(string $tenant, string $token)
    {
        tenancy()->initialize($tenant);
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token inválido ou expirado.'], 403);
        }

        /**
         * @var view-string $view
         */
        $view = 'auth.set-password';

        return view($view, compact('token', 'tenant', 'user'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPassword(Request $request, string $tenant, string $token)
    {
        tenancy()->initialize($tenant);

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            throw new \Exception('Token inválido ou usuário não encontrado');
        }

        $password = $request->input('password');

        if (!is_string($password)) {
            throw new \RuntimeException('A senha deve ser uma string.');
        }

        $user->password = Hash::make($password);
        $user->remember_token = null;
        $user->save();

        $protocol = config('app.protocol');
        $domain = config('app.external_tenant_url');

        if (!is_string($protocol) || !is_string($domain)) {
            throw new \RuntimeException('Configurações de URL inválidas');
        }

        $tenant = (string) $tenant;

        $link = "{$protocol}://{$tenant}.{$domain}/login";

        return redirect()->away($link);
    }
}
