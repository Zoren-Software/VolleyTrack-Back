<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as GuzzleClient;
use App\Services\GitHubService;
use Socialite;

class LoginController extends Controller
{
    /**
     * @return Socialite
     */
    public function gitHubRedirect()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * @return [type]
     */
    public function gitHubCallback() {
        
        $user = Socialite::driver('github')->user();

        

        return redirect()->route('horizon.index');
    }
}