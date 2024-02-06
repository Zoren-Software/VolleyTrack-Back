<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Model;

final class DiscordService extends Model
{
    private $webhookErrors;

    private $webhookPayments;

    private $client;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;

        $this->webhookErrors = config('services.discord.webhook_errors');
        $this->webhookPayments = config('services.discord.webhook_payments');

        if (!$this->webhookErrors || !$this->webhookPayments) {
            throw new \Throwable('Variáveis de conexão do Discord não declaradas');
        }
    }

    /**
     * @param  Throwable  $exception
     * @param  string  $message
     *
     * @codeCoverageIgnore
     */
    public function sendError(\Throwable $error, string $author): void
    {
        try {
            $data = [
                'content' => null,
                'embeds' => [
                    [
                        'title' => ':warning: ' . $error->getMessage() . ' :warning:',
                        'description' => $error->getMessage(),
                        'url' => url()->current(),
                        'color' => 16711680,
                        'fields' => [
                            [
                                'name' => 'ERROR Resume:',
                                'value' => 'File: ' . $error->getFile() . " \n In line: " . $error->getLine(),
                            ],
                            [
                                'name' => 'ERROR Code:',
                                'value' => $error->getCode(),
                            ],
                            [
                                'name' => 'APP_ENV:',
                                'value' => config('app.env'),
                            ],
                            [
                                'name' => 'APP_DEBUG:',
                                'value' => config('app.debug'),
                            ],
                            [
                                'name' => 'Tenant ID:',
                                'value' => tenant('id'),
                            ],
                        ],
                        'author' => [
                            'name' => 'ERROR ' . $author,
                            'icon_url' => 'https://cdn.icon-icons.com/icons2/1808/PNG/64/bug_115148.png',
                        ],
                        'timestamp' => date('Y-m-d H:i:s'),
                    ],
                ],
            ];

            if (auth()->user()) {
                $data['embeds'][0]['fields'][] = [
                    'name' => 'User ID:',
                    'value' => auth()->user()->id,
                ];
            }

            $this->client->post(
                $this->webhookErrors,
                [
                    'json' => $data,
                ]
            );
        } catch (ClientException $e) {
            throw new \Throwable('Erro ao enviar mensagem para o Discord');
        }
    }
}
