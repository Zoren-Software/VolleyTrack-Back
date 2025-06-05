<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Central\User;
use App\Services\GitHubService;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LoginGitHubController extends Controller
{
    /**
     * @codeCoverageIgnore
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function githubRedirect()
    {
        return Socialite::driver('github')
            ->redirect();
    }

    /**
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function githubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $githubService = new GitHubService;

        $login = $githubService->verifyPermissionUser($githubUser->getNickname() ?? '');

        if (!$login) {
            return redirect()->route('welcome-horizon', ['error' => 'Você não tem permissão para acessar o Horizon']);
        }

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

        /** @var StatefulGuard $guard */
        $guard = auth()->guard('web');
        $guard->login($user);

        return redirect()->route('horizon.index');
    }

    /**
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        /** @var StatefulGuard $guard */
        $guard = auth()->guard('web');
        $guard->logout();

        return redirect()->route('welcome-horizon');
    }
}
