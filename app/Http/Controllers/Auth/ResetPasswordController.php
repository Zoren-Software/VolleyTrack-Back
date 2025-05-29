<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * @codeCoverageIgnore
     *
     * @param  null  $token
     * @return \Illuminate\Contracts\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        /**
         * @var view-string $view
         */
        $view = 'auth.passwords.reset';

        return view($view)->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function postReset(Request $request)
    {
        $token = $request->input('token');

        /**
         * @var view-string $view
         */
        $view = 'auth.passwords.reset';

        return view($view)->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }
}
