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
     * Listagem de configurações de notificação
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
     * @test
     * 
     * @dataProvider notificationSettingActiveEmailAndSystemEditSuccess
     * @dataProvider notificationSettingDesactiveEmailAndSystemEditSuccess
     * @dataProvider notificationSettingActiveEmailEditSuccess
     * @dataProvider notificationSettingActiveSystemEditSuccess
     * 
     * TODO - Falta criar os cenários de erro do request e mensagens traduzidas
     * 
     * @return void
     */
    public function notificationSettingEdit(
        $data,
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
    )
    {
        $user = User::factory()->create(); 

        $this->be($user);

        $parameters['id'] = $user->notificationSettings
            ->firstWhere('notification_type_id', $parameters['notificationTypeId'])
            ?->id;
        
        $response = $this->graphQL(
            'notificationSettingEdit',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, false, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    public static function notificationSettingActiveEmailAndSystemEditSuccess() {
        return [
            'notification setting edit, active email and system notification type account confirmation' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 1,
                    'viaEmail' => true,
                    'viaSystem' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
            'notification setting edit, active email and system notification type training created' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 2,
                    'viaEmail' => true,
                    'viaSystem' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
            'notification setting edit, active email and system notification type training canceled' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 3,
                    'viaEmail' => true,
                    'viaSystem' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
        ];
    }

    public static function notificationSettingActiveEmailEditSuccess() {
        return [
            'notification setting edit, active email notification type account confirmation' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 1,
                    'viaEmail' => true,
                    'viaSystem' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
            'notification setting edit, active email notification type training created' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 2,
                    'viaEmail' => true,
                    'viaSystem' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
            'notification setting edit, active email notification type training canceled' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 3,
                    'viaEmail' => true,
                    'viaSystem' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
        ];
    }

    public static function notificationSettingActiveSystemEditSuccess() {
        return [
            'notification setting edit, active system notification type account confirmation' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 1,
                    'viaEmail' => false,
                    'viaSystem' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
            'notification setting edit, active system notification type training created' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 2,
                    'viaEmail' => false,
                    'viaSystem' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
            'notification setting edit, active system notification type training canceled' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 3,
                    'viaEmail' => false,
                    'viaSystem' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
        ];
    }

    public static function notificationSettingDesactiveEmailAndSystemEditSuccess() {
        return [
            'notification setting edit, desactive email and system notification type account confirmation' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 1,
                    'viaEmail' => false,
                    'viaSystem' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
            'notification setting edit, desactive email and system notification type training created' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 2,
                    'viaEmail' => false,
                    'viaSystem' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
            'notification setting edit, desactive email and system notification type training canceled' => [
                'data' => [
                    'error' => null,
                    'message_expected' => 'Configuração de notificação editada com sucesso',
                ],
                'parameters' => [
                    'notificationTypeId' => 3,
                    'viaEmail' => false,
                    'viaSystem' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'notificationSettingEdit' => self::$data,
                    ],
                ],
            ],
        ];
    }
}
