<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    /**
     * @param string $tenant
     * @param string $token
     * 
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
     * @param \Illuminate\Http\Request $request
     * @param string $tenant
     * @param string $token
     * 
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

        $user->password = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();

        $link = config('app.protocol') . '://' . $tenant . '.' . config('app.external_tenant_url') . '/login';

        return redirect()->away($link);
    }
}
