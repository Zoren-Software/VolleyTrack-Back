<?php

namespace Tests\Feature\GraphQL;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    protected bool $graphql = true;

    protected bool $tenancy = true;

    protected bool $login = true;

    /**
     * @var array<int, string>
     */
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->limparAmbiente();
    }

    protected function tearDown(): void
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
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function notification_list()
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
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('notificationReadProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function notifications_read(
        array $data,
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasLogin
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
            $notification = $user->notifications()->firstOrFail();
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function notificationReadProvider(): array
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
