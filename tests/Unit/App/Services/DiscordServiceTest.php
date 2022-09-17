<?php

namespace Tests\Unit\App\Services;

use Tests\TestCase;
use App\Services\DiscordService;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use Throwable;
use Illuminate\Database\Eloquent\Model;

class DiscordServiceTest extends TestCase
{
    /**
     * A basic unit test construct.
     *
     * @return void
     */
    public function test_construct()
    {
        $clientMock = $this->createMock(Client::class);
        $discordService = new DiscordService($clientMock);
        $this->assertInstanceOf(DiscordService::class, $discordService);
    }

    /**
     * A basic unit test sendError.
     *
     * @return void
     */
    public function test_construct_error_declare_variables()
    {
        $clientMock = $this->createMock(Client::class);

        Config::set('services.discord.webhook_errors', null);
        Config::set('services.discord.webhook_payments', null);

        $this->expectException(Throwable::class);

        $discordService = new DiscordService($clientMock);
        $this->assertInstanceOf(DiscordService::class, $discordService);
    }
}
