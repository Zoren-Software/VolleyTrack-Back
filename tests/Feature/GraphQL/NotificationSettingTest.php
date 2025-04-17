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
     * @dataProvider notificationSettingRequiredParametersEditError
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

        if ($parameters['notificationTypeId'] === false) {
            unset($parameters['notificationTypeId']);
        } elseif (!is_numeric($parameters['notificationTypeId'])) {
            // mantém como está
        } else {
            $parameters['notificationTypeId'] = (int) $parameters['notificationTypeId'];
        }

        if ($parameters['id'] === false) {
            unset($parameters['id']);
        } elseif($parameters['id'] === 'test') {

        } else{
            $parameters['id'] = $user->notificationSettings
                ->when(isset($parameters['notificationTypeId']), function ($query) use ($parameters) {
                    return $query->where('notification_type_id', $parameters['notificationTypeId']);
                })
                ->first()
                ?->id;
        }

        $response = $this->graphQL(
            'notificationSettingEdit',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, true, $expectedMessage);

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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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
                    'id' => true,
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

    public static function notificationSettingRequiredParametersEditError(): array
    {
        return [
            'notification setting edit, input id is required' => [
                'data' => [
                    'error' => true,
                    'message_expected' => 'Configuração de notificação não editada',
                ],
                'parameters' => [
                    'id' => false,
                    'notificationTypeId' => 1,
                    'viaEmail' => null,
                    'viaSystem' => null,
                ],
                'typeMessageError' => 'id',
                'expectedMessage' => 'NotificationSettingEdit.id_required',
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'path',
                            'extensions',
                        ],
                    ],
                ],
            ],
            'notification setting edit, input id not is boolean' => [
                'data' => [
                    'error' => true,
                    'message_expected' => 'Configuração de notificação não editada',
                ],
                'parameters' => [
                    'id' => 'test',
                    'notificationTypeId' => 1,
                    'viaEmail' => null,
                    'viaSystem' => null,
                ],
                'typeMessageError' => 'id',
                'expectedMessage' => 'NotificationSettingEdit.id_integer',
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'path',
                            'extensions',
                        ],
                    ],
                ],
            ],
            'notification setting edit, input notificationTypeId is required' => [
                'data' => [
                    'error' => true,
                    'message_expected' => 'Configuração de notificação não editada',
                ],
                'parameters' => [
                    'id' => true,
                    'notificationTypeId' => false,
                    'viaEmail' => true,
                    'viaSystem' => true,
                ],
                'typeMessageError' => 'notificationTypeId',
                'expectedMessage' => 'NotificationSettingEdit.notificationTypeId_required',
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'path',
                            'extensions',
                        ],
                    ],
                ],
            ],
        ];
    }
}
