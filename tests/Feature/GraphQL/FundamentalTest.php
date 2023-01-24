<?php

namespace Tests\Feature\GraphQL;

use App\Models\Fundamental;
use Faker\Factory as Faker;
use Tests\TestCase;

class FundamentalTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $permission = 'technician';

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
     * @test
     *
     * @return void
     */
    public function fundamentalsList()
    {
        Fundamental::factory()->make()->save();

        $this->graphQL(
            'fundamentals',
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
                'fundamentals' => [
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
     * @test
     * 
     * @dataProvider infoProvider
     *
     * @return void
     */
    public function fundamentalInfo(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $permission
    )
    {
        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        $this->checkPermission($permission, $this->permission, 'edit-fundamental');
        $this->checkPermission($permission, $this->permission, 'view-fundamental');

        $response = $this->graphQL(
            'fundamental',
            [
                'id' => $fundamental->id,
            ],
            $this->data,
            'query',
            false
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $permission,
            $expectedMessage
        );
        
        if($permission) {
            $response->assertJsonStructure([
                'data' => [
                    'fundamental' => $this->data,
                ],
            ])->assertStatus(200);
        }
    }

    /**
     * @return array
     */
    public function infoProvider()
    {
        $configEdit = ['configEdit'];

        return [
            'with permission' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'config' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'without permission' => [
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $configEdit,
                ],
                'permission' => false,
            ],
        ];
    }

    /**
     * Método de criação de um fundamento.
     *
     * @dataProvider fundamentalCreateProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function fundamentalCreate(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission
        ) {
        $this->checkPermission($permission, $this->permission, 'edit-fundamental');

        $response = $this->graphQL(
            'fundamentalCreate',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $permission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function fundamentalCreateProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name;
        $fundamentalCreate = ['fundamentalCreate'];

        return [
            'create fundamental, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'fundamentalCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create fundamental without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalCreate,
                ],
                'permission' => false,
            ],
            'name field is not unique, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'FundamentalCreate.name_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalCreate,
                ],
                'permission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'FundamentalCreate.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalCreate,
                ],
                'permission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'FundamentalCreate.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalCreate,
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um fundamento.
     *
     * @dataProvider fundamentalEditProvider
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @return void
     */
    public function fundamentalEdit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission
        ) {
        $this->checkPermission($permission, $this->permission, 'edit-fundamental');

        $fundamentalExist = Fundamental::factory()->make();
        $fundamentalExist->save();
        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        $parameters['id'] = $fundamental->id;

        if ($expectedMessage == 'FundamentalEdit.name_unique') {
            $parameters['name'] = $fundamentalExist->name;
        }

        $response = $this->graphQL(
            'fundamentalEdit',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $permission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function fundamentalEditProvider()
    {
        $faker = Faker::create();
        $userId = 2;
        $fundamentalEdit = ['fundamentalEdit'];

        return [
            'edit fundamental without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalEdit,
                ],
                'permission' => false,
            ],
            'edit fundamental, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'fundamentalEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'FundamentalEdit.name_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalEdit,
                ],
                'permission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'FundamentalEdit.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalEdit,
                ],
                'permission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'FundamentalEdit.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalEdit,
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * Método de exclusão de um fundamento.
     *
     * @author Maicon Cerutti
     *
     * @dataProvider fundamentalDeleteProvider
     *
     * @test
     *
     * @return void
     */
    public function fundamentalDelete($data, $typeMessageError, $expectedMessage, $expected, $permission)
    {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'edit-fundamental');

        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        $parameters['id'] = $fundamental->id;

        if ($data['error'] != null) {
            $parameters['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'fundamentalDelete',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $permission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @author Maicon Cerutti
     *
     * @return array
     */
    public function fundamentalDeleteProvider()
    {
        $fundamentalDelete = ['fundamentalDelete'];

        return [
            'delete fundamental, success' => [
                [
                    'error' => null,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'fundamentalDelete' => [$this->data],
                    ],
                ],
                'permission' => true,
            ],
            'delete fundamental without permission, expected error' => [
                [
                    'error' => null,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalDelete,
                ],
                'permission' => false,
            ],
            'delete fundamental that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'type_message_error' => 'message',
                'expected_message' => 'internal',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalDelete,
                ],
                'permission' => true,
            ],
        ];
    }
}
