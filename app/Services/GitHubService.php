<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Database\Eloquent\Model;

final class GitHubService extends Model
{
    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @codeCoverageIgnore
     *
     * @throws \RuntimeException
     */
    public function __construct(?GuzzleClient $client = null)
    {
        $this->client = $client ?? new GuzzleClient;

        $accessToken = config('services.github.access_token');
        if (!is_string($accessToken)) {
            throw new \RuntimeException('Variáveis de conexão do GitHub não declaradas ou inválidas');
        }

        $this->accessToken = $accessToken;
    }

    /**
     * @codeCoverageIgnore
     * Verifica se o usuário tem permissão para acessar o repositório.
     */
    public function verifyPermissionUser(string $nickName): bool
    {
        try {
            $response = $this->client->get(
                "https://api.github.com/repos/Zoren-Software/VoleiClub/collaborators/$nickName",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->accessToken,
                    ],
                ]
            );

            if ($response->getStatusCode() === 204) {
                return true;
            }

            if ($response->getStatusCode() === 404) {
                return false;
            }

            if ($response->getStatusCode() === 401) {
                throw new \RuntimeException('Token de acesso inválido');
            }
        } catch (\Exception $e) {
            report($e);
        }

        return false;
    }
}
