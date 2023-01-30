<?php

namespace Tests\Feature\GraphQL;

use App\Models\Notification;
use App\Models\User;
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
     *
     * @test
     *
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

    /**
     * Método de leitura de notificação.
     *
     * @author Maicon Cerutti
     *
     * @dataProvider notificationReadProvider
     *
     * @test
     *
     * @return void
     */
    public function notificationsRead(
        $data,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $hasLogin
    ) {
        if ($hasLogin) {
            $user = User::factory()->create();

            Notification::factory(10)
                ->setNotifiableId($user->id)
                ->create();

            $this->be($user);
        } else {
            $this->login = false;
        }

        $response = $this->graphQL(
            'notificationsRead',
            [],
            [
                'message',
            ],
            'mutation',
            false,
            false
        );

        $this->assertMessageError($typeMessageError, $response, $hasLogin, $expectedMessage);

        if ($data['error'] === null) {
            $response
                ->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @author Maicon Cerutti
     *
     * @return array
     */
    public function notificationReadProvider()
    {
        return [
            'read all notifications, success' => [
                [
                    'error' => null,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'notificationsRead' => [
                            'message',
                        ],
                    ],
                ],
                'hasLogin' => true,
            ],
            'read all notifications, error' => [
                [
                    'error' => 'error',
                ],
                'type_message_error' => 'message',
                'expected_message' => 'Unauthenticated.',
                'expected' => [
                    'errors' => $this->errors,
                ],
                'hasLogin' => false,
            ],
        ];
    }
}
