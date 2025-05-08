<?php

namespace Tests\Feature\GraphQL;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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

    public function setUp(): void
    {
        parent::setUp();
        $this->limparAmbiente();
    }

    public function tearDown(): void
    {
        $this->limparAmbiente();

        parent::tearDown();
    }

    private function limparAmbiente(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Notification::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * A basic feature test example.
     *
     * @test
     *
     * @return void
     */
    public function notificationList()
    {
        $user = User::factory()->create();

        Notification::factory(5)
            ->setNotifiableId($user->id)
            ->setTypeNotification('TrainingNotification')
            ->create();

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

        if ($parameters['id'] && $hasLogin) {
            $notification = $user->notifications()->first();
            $parameters['id'] = [$notification->id];
        } else {
            unset($parameters['id']);
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
                'typeMessageError' => false,
                'expectedMessage' => false,
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
                'typeMessageError' => false,
                'expectedMessage' => false,
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
                'typeMessageError' => false,
                'expectedMessage' => false,
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
                'typeMessageError' => 'message',
                'expectedMessage' => 'Unauthenticated.',
                'expected' => [
                    'errors' => self::$errors,
                ],
                'hasLogin' => false,
            ],
        ];
    }
}
