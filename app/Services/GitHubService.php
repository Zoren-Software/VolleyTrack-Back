<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Socialite;

final class GitHubService extends Model
{
    /**
     * @codeCoverageIgnore
     *
     * @param  GuzzleClient  $client
     */
    public function __construct(GuzzleClient $client = null)
    {
        $this->client = $client ?? new GuzzleClient();

        $this->accessToken = config('services.github.access_token');

        if (!$this->accessToken) {
            throw new \Throwable('Variáveis de conexão do GitHub não declaradas');
        }
    }

    /**
     * Verifica se o usuário tem permissão para acessar o repositório.
     *
     * @param string $nickName
     *
     * @return bool
     */
    public function verifyPermissionUser(string $nickName)
    {
        try {
            $response = $this->client->get(
                "https://api.github.com/repos/Zoren-Software/VoleiClub/collaborators/$nickName",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('GITHUB_ACCESS_TOKEN'),
                    ],
                ]
            );
            // se o status code for 204, o usuário tem acesso ao repositório
            if ($response->getStatusCode() == 204) {
                return true;
            } elseif ($response->getStatusCode() == 404) {
                return false;
            } elseif ($response->getStatusCode() == 401) {
                throw new \Exception('Token de acesso inválido');
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}