<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function showSetPasswordForm(string $tenant, string $token)
    {
        tenancy()->initialize($tenant);
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token inválido ou expirado.'], 403);
        }

        return view('auth.set-password', compact('token', 'tenant', 'user'));
    }

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

        $link = env('APP_PROTOCOL') . '://' . $tenant . '.' . env('LINK_EXTERNAL_TENANT_URL') . '/login';

        return redirect()->away($link);
    }
}
