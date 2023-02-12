<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * @codeCoverageIgnore
     *
     * @param  Request  $request
     * @param  null  $token
     * @return [type]
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * @codeCoverageIgnore
     *
     * @param  Request  $request
     * @return [type]
     */
    public function postReset(Request $request)
    {
        $token = $request->input('token');

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
