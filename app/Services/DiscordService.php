<?php

namespace App\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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
            Log::error('Discord webhooks not configured');
        }
    }

    /**
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

            // if (auth()->user()) {
            //     $data['embeds'][0]['fields'][] = [
            //         'name' => 'User ID:',
            //         'value' => auth()->user()->id,
            //     ];
            // }

            $this->client->post(
                $this->webhookErrors,
                [
                    'json' => $data,
                ]
            );
        } catch (ClientException $e) {

            // fazer mensagem de erro
            Log::error('Erro ao enviar mensagem para o Discord: ' . $e->getMessage());
        }
    }
}
