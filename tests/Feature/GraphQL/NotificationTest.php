<?php

namespace Tests\Feature\GraphQL;

use App\Models\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $data = [
        'id',
        'type',
        'notifiableType',
        'notifiableId',
        'data',
        'readAt',
        'createdAt',
        'updatedAt',
    ];

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function notificationList()
    {
        Notification::factory()->make()->save();

        $this->graphQL(
            'notifications',
            [
                'read' => false,
                'first' => 10,
                'page' => 1,
            ],
            [
                'paginatorInfo' => $this->paginatorInfo,
                'data' => $this->data,
            ],
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'notifications' => [
                    'paginatorInfo' => $this->paginatorInfo,
                    'data' => [
                        '*' => $this->data,
                    ],
                ],
            ],
        ])->assertStatus(200);
    }
}
