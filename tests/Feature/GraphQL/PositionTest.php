<?php

namespace Tests\Feature\GraphQL;

use App\Models\Position;
use Faker\Factory as Faker;
use Tests\TestCase;

class PositionTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $permission = 'Técnico';

    private $data = [
        'id',
        'name',
        'userId',
        'createdAt',
        'updatedAt',
    ];

    /**
     * Listagem de todos os fundamentos.
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_positions_list()
    {
        Position::factory()->make()->save();

        $this->graphQL(
            'positions',
            [
                'name' => '%%',
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
                'positions' => [
                    'paginatorInfo' => $this->paginatorInfo,
                    'data' => [
                        '*' => $this->data,
                    ],
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Listagem de um fundamento
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_position_info()
    {
        $position = Position::factory()->make();
        $position->save();

        $this->graphQL(
            'position',
            [
                'id' => $position->id,
            ],
            $this->data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'position' => $this->data,
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de criação de um fundamento.
     *
     * @dataProvider positionCreateProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_position_create($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->checkPermission($permission, $this->permission, 'create-position');

        $response = $this->graphQL(
            'positionCreate',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($type_message_error, $response, $permission, $expected_message);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function positionCreateProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name;
        $positionCreate = ['positionCreate'];

        return [
            'create position, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'positionCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create position without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionCreate,
                ],
                'permission' => false,
            ],
            'name field is not unique, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'PositionCreate.name_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionCreate,
                ],
                'permission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'PositionCreate.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionCreate,
                ],
                'permission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'PositionCreate.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionCreate,
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um fundamento.
     *
     * @dataProvider positionEditProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_position_edit($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->checkPermission($permission, $this->permission, 'edit-position');

        $positionExist = Position::factory()->make();
        $positionExist->save();
        $position = Position::factory()->make();
        $position->save();

        $parameters['id'] = $position->id;

        if ($expected_message == 'PositionEdit.name_unique') {
            $parameters['name'] = $positionExist->name;
        }

        $response = $this->graphQL(
            'positionEdit',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($type_message_error, $response, $permission, $expected_message);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function positionEditProvider()
    {
        $faker = Faker::create();
        $userId = 2;
        $positionEdit = ['positionEdit'];

        return [
            'edit position without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionEdit,
                ],
                'permission' => false,
            ],
            'edit position, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'positionEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'PositionEdit.name_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionEdit,
                ],
                'permission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'PositionEdit.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionEdit,
                ],
                'permission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'PositionEdit.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionEdit,
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * Método de exclusão de uma posição.
     *
     * @author Maicon Cerutti
     *
     * @dataProvider positionDeleteProvider
     *
     * @return void
     */
    public function test_position_delete($data, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'delete-position');

        $position = Position::factory()->make();
        $position->save();

        $parameters['id'] = $position->id;

        if ($data['error'] != null) {
            $parameters['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'positionDelete',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($type_message_error, $response, $permission, $expected_message);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function positionDeleteProvider()
    {
        $positionDelete = ['positionDelete'];

        return [
            'delete position, success' => [
                [
                    'error' => null,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'positionDelete' => [$this->data],
                    ],
                ],
                'permission' => true,
            ],
            'delete position without permission, expected error' => [
                [
                    'error' => null,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionDelete,
                ],
                'permission' => false,
            ],
            'delete position that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'type_message_error' => 'message',
                'expected_message' => 'internal',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $positionDelete,
                ],
                'permission' => true,
            ],
        ];
    }
}
