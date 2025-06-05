<?php

namespace Tests\Feature\GraphQL;

use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationSettingTest extends TestCase
{
    /**
     * @var bool
     */
    protected $graphql = true;

    /**
     * @var bool
     */
    protected $tenancy = true;

    /**
     * @var bool
     */
    protected $login = true;

    /**
     * @var array<int, string>
     */
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

        NotificationSetting::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Listagem de configurações de notificação
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function notification_settings_list()
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
        ]);

        $response->assertStatus(200);
    }

    /**
     * Edição de configurações de notificação
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     *
     * TODO - Falta criar os cenários de erro do request e mensagens traduzidas
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('notificationSettingActiveEmailAndSystemEditSuccess')]
    #[\PHPUnit\Framework\Attributes\DataProvider('notificationSettingDesactiveEmailAndSystemEditSuccess')]
    #[\PHPUnit\Framework\Attributes\DataProvider('notificationSettingActiveEmailEditSuccess')]
    #[\PHPUnit\Framework\Attributes\DataProvider('notificationSettingActiveSystemEditSuccess')]
    #[\PHPUnit\Framework\Attributes\DataProvider('notificationSettingRequiredParametersEditError')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function notification_setting_edit(
        array $data,
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
    ) {
        $user = User::factory()->create();

        $this->be($user);

        if ($parameters['notificationTypeId'] === false) {
            unset($parameters['notificationTypeId']);
        } elseif ($parameters['notificationTypeId'] === 'notExists') {
            $maxId = NotificationSetting::max('notification_type_id');
            $parameters['notificationTypeId'] = (is_int($maxId) ? $maxId : 0) + 1;
        } elseif ($parameters['notificationTypeId'] === 'test') {
            $parameters['notificationTypeId'] = 'test';
        } elseif (!is_numeric($parameters['notificationTypeId'])) {
            // mantém como está
        } else {
            $parameters['notificationTypeId'] = (int) $parameters['notificationTypeId'];
        }

        if ($parameters['id'] === false) {
            unset($parameters['id']);
        } elseif ($parameters['id'] === 'test') {

        } elseif ($parameters['id'] === 'notExists') {
            $maxId = NotificationSetting::max('id');
            $parameters['id'] = (is_int($maxId) ? $maxId : 0) + 1;
        } else {
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

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function notificationSettingActiveEmailAndSystemEditSuccess(): array
    {
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

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function notificationSettingActiveEmailEditSuccess(): array
    {
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

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function notificationSettingActiveSystemEditSuccess(): array
    {
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

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function notificationSettingDesactiveEmailAndSystemEditSuccess(): array
    {
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

    /**
     * @return array<string, array<int|string, mixed>>
     */
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
                    'viaEmail' => true,
                    'viaSystem' => true,
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
            'notification setting edit, input id not is integer' => [
                'data' => [
                    'error' => true,
                    'message_expected' => 'Configuração de notificação não editada',
                ],
                'parameters' => [
                    'id' => 'test',
                    'notificationTypeId' => 1,
                    'viaEmail' => true,
                    'viaSystem' => true,
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
            'notification setting edit, input id not exists' => [
                'data' => [
                    'error' => true,
                    'message_expected' => 'Configuração de notificação não editada',
                ],
                'parameters' => [
                    'id' => 'notExists',
                    'notificationTypeId' => 1,
                    'viaEmail' => true,
                    'viaSystem' => true,
                ],
                'typeMessageError' => 'id',
                'expectedMessage' => 'NotificationSettingEdit.id_exists',
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
            'notification setting edit, input notificationTypeId not exists' => [
                'data' => [
                    'error' => true,
                    'message_expected' => 'Configuração de notificação não editada',
                ],
                'parameters' => [
                    'id' => true,
                    'notificationTypeId' => 'notExists',
                    'viaEmail' => true,
                    'viaSystem' => true,
                ],
                'typeMessageError' => 'notificationTypeId',
                'expectedMessage' => 'NotificationSettingEdit.notificationTypeId_exists',
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
            'notification setting edit, input viaEmail is required' => [
                'data' => [
                    'error' => true,
                    'message_expected' => 'Configuração de notificação não editada',
                ],
                'parameters' => [
                    'id' => true,
                    'notificationTypeId' => 1,
                    'viaEmail' => null,
                    'viaSystem' => true,
                ],
                'typeMessageError' => 'viaEmail',
                'expectedMessage' => 'NotificationSettingEdit.viaEmail_required',
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
            'notification setting edit, input viaSystem is required' => [
                'data' => [
                    'error' => true,
                    'message_expected' => 'Configuração de notificação não editada',
                ],
                'parameters' => [
                    'id' => true,
                    'notificationTypeId' => 1,
                    'viaEmail' => false,
                    'viaSystem' => null,
                ],
                'typeMessageError' => 'viaSystem',
                'expectedMessage' => 'NotificationSettingEdit.viaSystem_required',
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
