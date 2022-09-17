<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

class DiscordService extends Model
{
    protected $webhookErrors;
    protected $webhookPayments;

    public function __construct()
    {
        $this->webhookErrors = config('services.discord.webhook_errors');
        $this->webhookPayments = config('services.discord.webhook_payments');

        if (! $this->webhookErrors || ! $this->webhookPayments) {
            throw new \Throwable('Variáveis de conexão não declaradas');
        }
    }

    public function sendError(\Throwable $error, String $author): void
    {
        try {
            $client = new GuzzleClient();
            $client->post(
                $this->webhookErrors,
                [
                    'json' => $this->formatMessageError($error, $author)
                ]
            );
        } catch (ClientException $e) {
            throw new \Throwable('Erro ao enviar mensagem para o Discord');

        }
    }

    private function formatMessageError(\Throwable $error, String $author)
    {
        $data = [
            "content" => null,
            "embeds" => [
                [
                    "title" => ":warning: " . $error->getMessage() . " :warning:",
                    "description" => $error->getMessage(),
                    "url" => url()->current(),
                    "color" => 16711680,
                    "fields" => [
                        [
                            "name" => "ERROR Resume:",
                            "value" => "File: " . $error->getFile() . " \n In line: " . $error->getLine(),
                        ],
                        [
                            "name" => "ERROR Code:",
                            "value" => $error->getCode(),
                        ],
                        [
                            "name" => "APP_ENV:",
                            "value" => config('app.env'),
                        ],
                        [
                            "name" => "APP_DEBUG:",
                            "value" => config('app.debug'),
                        ],
                        [
                            "name" => "Tenant ID:",
                            "value" => tenant('id'),
                        ]
                    ],
                    "author" => [
                        "name" => 'ERROR ' . $author,
                        "url" => "http://test.voleiclub.local/url-tenant",
                        "icon_url" => "https://cdn.icon-icons.com/icons2/1808/PNG/64/bug_115148.png"
                    ],
                    "timestamp" => date('Y-m-d H:i:s')
                ]
            ],
        ];

        if (auth()->user()) {
            $data['embeds'][0]['fields'][] = [
                "name" => "User ID:",
                "value" => auth()->user()->id,
            ];
        }

        return $data;
    }
}
