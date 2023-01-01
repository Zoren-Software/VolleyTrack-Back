<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;
use Faker\Factory as Faker;

class ConfigTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $permission = 'Técnico';

    private $data = [
        'id',
        'userId',
        'nameTenant',
        'languageId',
        'createdAt',
        'updatedAt',
    ];

    /**
     * Listagem de configurações.
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_config_info()
    {
        $this->graphQL(
            'config',
            [
                'id' => 1,
            ],
            $this->data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'config' => $this->data,
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de edição de configurações.
     *
     * @dataProvider ConfigEditProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_config_edit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission
    ) {
        $this->checkPermission($permission, $this->permission, 'edit-config');

        $parameters['id'] = 1;

        $response = $this->graphQL(
            'configEdit',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $permission,
            $expectedMessage
        );

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function ConfigEditProvider()
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
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $configEdit,
                ],
                'permission' => false,
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
                        'configEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'nameTenant field is required, expected error' => [
                [
                    'nameTenant' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'nameTenant',
                'expected_message' => 'ConfigEdit.name_tenant_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $configEdit,
                ],
                'permission' => true,
            ],
            'nameTenant field is min 3 characteres, expected error' => [
                [
                    'nameTenant' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'nameTenant',
                'expected_message' => 'ConfigEdit.name_tenant_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $configEdit,
                ],
                'permission' => true,
            ],
        ];
    }
}
