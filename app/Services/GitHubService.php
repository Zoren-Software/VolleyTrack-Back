<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Socialite;

final class GibHubService extends Model
{
    /**
     * @codeCoverageIgnore
     *
     * @param  GuzzleClient  $client
     */
    public function __construct(GuzzleClient $client = null)
    {
        $this->client = $client;
    }

    /**
     * @codeCoverageIgnore
     *
     */
    public function gitHubLogin()
    {
        return Socialite::driver('github')->redirect();
    }

    public function gitHubCallback()
    {
        try {
     
           // TODO - Passar função pra cá
     
        } catch (\Exception $e) {
            dd($e);
            report($e);
        }
    }

    public function validationUserGitHubAccess(){

    }
}
