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

    public static $data = [
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
                'paginatorInfo' => self::$paginatorInfo,
                'data' => self::$data,
            ],
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'notifications' => [
                    'paginatorInfo' => self::$paginatorInfo,
                    'data' => [
                        '*' => self::$data,
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
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $hasLogin
    ) {
        if ($hasLogin) {
            $user = User::factory()->create();

            Notification::factory(5)
                ->setNotifiableId($user->id)
                ->setTypeNotification('TrainingNotification')
                ->create();

            Notification::factory(5)
                ->setNotifiableId($user->id)
                ->setTypeNotification('CancelTrainingNotification')
                ->create();
            
            Notification::factory(5)
                ->setNotifiableId($user->id)
                ->setTypeNotification('ConfirmationTrainingNotification')
                ->create();

            $this->be($user);
        } else {
            $this->login = false;
        }

        if($parameters['id'] && $hasLogin) {
            $notification = $user->notifications()->first();
            $parameters['id'] = [$notification->id];
        }

        $response = $this->graphQL(
            'notificationsRead',
            $parameters,
            [
                'message',
            ],
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasLogin, $expectedMessage);

        if ($data['error'] === null) {
            $this->assertEquals(
                $data['message_expected'],
                $response->json('data.notificationsRead.message')
            );

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
    public static function notificationReadProvider()
    {
        return [
            'read the last 10 notifications, success' => [
                [
                    'error' => null,
                    'message_expected' => '10 notificações recentes foram lidas.',
                ],
                'parameters' => [
                    'markAllAsRead' => false,
                    'recentToDeleteCount' => 10,
                    'id' => false,
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
            'read all notifications, success' => [
                [
                    'error' => null,
                    'message_expected' => 'Todas as notificações foram lidas com sucesso!',
                ],
                'parameters' => [
                    'markAllAsRead' => true,
                    'recentToDeleteCount' => 1,
                    'id' => false,
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
            'read one notification, success' => [
                [
                    'error' => null,
                    'message_expected' => 'Notificação lida com sucesso!',
                ],
                'parameters' => [
                    'markAllAsRead' => true,
                    'recentToDeleteCount' => 1,
                    'id' => true,
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
                'parameters' => [
                    'markAllAsRead' => true,
                    'recentToDeleteCount' => 1,
                    'id' => false,
                ],
                'type_message_error' => 'message',
                'expected_message' => 'Unauthenticated.',
                'expected' => [
                    'errors' => self::$errors,
                ],
                'hasLogin' => false,
            ],
        ];
    }
}
