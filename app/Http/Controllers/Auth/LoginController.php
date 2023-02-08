<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as GuzzleClient;
use App\Services\GitHubService;
use App\Models\Central\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * @return Socialite
     */
    public function githubRedirect()
    {
        return Socialite::driver('github')
            ->redirect();
    }

    /**
     * @return [type]
     */
    public function githubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::updateOrCreate(
            ['github_id' => $githubUser->getId()],
            [
                'name' => $githubUser->getName(),
                'email' => $githubUser->getEmail(),
                'github_id' => $githubUser->getId(),
                'auth_type' => 'github',
                'password' => Hash::make($githubUser->getId()),
            ]
        );

        auth()->guard('web')->login($user);

        return redirect()->route('horizon.index');
    }
}
