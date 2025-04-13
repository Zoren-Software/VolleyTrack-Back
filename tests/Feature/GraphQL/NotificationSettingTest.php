<?php

namespace Tests\Feature\GraphQL;

use App\Models\NotificationSetting;
use App\Models\User;
use Tests\TestCase;

class NotificationSettingTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    public static $data = [
        'id',
        'userId',
        'notificationTypeId',
        'viaEmail',
        'viaSystem',
        'isActive',
        'createdAt',
        'updatedAt',
    ];

    /**
     * A basic feature test example.
     *
     * @test
     *
     * @return void
     */
    public function notificationList()
    {
        $response = $this->graphQL(
            'notificationsSettings',
            [
                'first' => 10,
                'page' => 1,
            ],
            [
                'paginatorInfo' => self::$paginatorInfo,
                'data' => self::$data,
            ],
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'notificationsSettings' => [
                    'paginatorInfo' => self::$paginatorInfo,
                    'data' => [
                        '*' => self::$data,
                    ],
                ],
            ],
        ])->assertStatus(200);
    }
}
