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
    public function notificationSettingsList()
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

    /**
     * Edição de configurações de notificação
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function notificationsSettingsEdit() 
    {
        $user = User::factory()->create(); 

        $this->be($user);

        // TODO - Parei aqui, para ver como fazer esse endpoint
        // iniciei o teste com base em outra classe, mas preciso ver mais detalhes de como fazer isso com eficiencia

        dd(auth()->user());

        if ($parameters['id'] && $hasLogin) {
            $notification = $user->notifications()->first();
            $parameters['id'] = [$notification->id];
        } else {
            unset($parameters['id']);
        }

        $response = $this->graphQL(
            'notificationsSettingsEdit',
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
}
