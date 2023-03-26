<?php

namespace Tests\Feature\GraphQL;

use Faker\Factory as Faker;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $role = 'technician';

    public static $data = [
        'id',
        'userId',
        'nameTenant',
        'languageId',
        'createdAt',
        'updatedAt',
    ];

    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-config');
        $this->checkPermission($hasPermission, $this->role, 'view-config');
    }

    /**
     * Listagem de configurações.
     *
     * @test
     *
     * @dataProvider infoProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function configInfo(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $response = $this->graphQL(
            'config',
            [
                'id' => 1,
            ],
            self::$data,
            'query',
            false
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $hasPermission,
            $expectedMessage
        );

        if ($hasPermission) {
            $response
                ->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @return array
     */
    public static function infoProvider()
    {
        return [
            'with permission' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'config' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'without permission' => [
                'type_message_error' => 'message',
                'expected_message' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                ],
                'hasPermission' => false,
            ],
        ];
    }

    /**
     * Método de edição de configurações.
     *
     * @dataProvider configEditProvider
     *
     * @test
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function configEdit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $parameters['id'] = 1;

        $response = $this->graphQL(
            'configEdit',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $hasPermission,
            $expectedMessage
        );

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public static function configEditProvider()
    {
        $faker = Faker::create();
        $userId = 2;
        $configEdit = ['configEdit'];

        return [
            'edit config without permission, expected error' => [
                [
                    'nameTenant' => $faker->name,
                    'userId' => $userId,
                    'languageId' => 1,
                ],
                'type_message_error' => 'message',
                'expected_message' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $configEdit,
                ],
                'hasPermission' => false,
            ],
            'edit config, success' => [
                [
                    'nameTenant' => $faker->name,
                    'userId' => $userId,
                    'languageId' => 1,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'configEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'nameTenant field is required, expected error' => [
                [
                    'nameTenant' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'nameTenant',
                'expected_message' => 'ConfigEdit.name_tenant_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $configEdit,
                ],
                'hasPermission' => true,
            ],
            'nameTenant field is min 3 characteres, expected error' => [
                [
                    'nameTenant' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'nameTenant',
                'expected_message' => 'ConfigEdit.name_tenant_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $configEdit,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
