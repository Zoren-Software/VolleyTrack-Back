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

    private $data = [
        'id',
        'name',
        'userId',
        'createdAt',
        'updatedAt'
    ];

    /**
     * Listagem de todos os fundamentos.
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_fundamentals_list()
    {
        Fundamental::factory()->make()->save();

        $response = $this->graphQL(
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
        );

        $response->assertJsonStructure([
            'data' => [
                'fundamentals' => [
                    'paginatorInfo' => $this->paginatorInfo,
                    'data' => [
                        '*' => $this->data
                    ]
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
    public function test_fundamental_info()
    {
        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        $response = $this->graphQL(
            'fundamental',
            [
                'id' => $fundamental->id,
            ],
            $this->data,
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'fundamental' => $this->data,
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de criação de um fundamento.
     *
     * @dataProvider fundamentalCreateProvider
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_fundamental_create($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->checkPermission($permission, 'Técnico', 'create-fundamental');

        $response = $this->graphQL(
            'fundamentalCreate',
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
     *
     * @return Array
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
                    'data' => $fundamentalCreate
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
                    'data' => $fundamentalCreate
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
                    'data' => $fundamentalCreate
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
                    'data' => $fundamentalCreate
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um fundamento.
     *
     * @dataProvider fundamentalEditProvider
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_fundamental_edit($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->checkPermission($permission, 'Técnico', 'edit-fundamental');

        $fundamentalExist = Fundamental::factory()->make();
        $fundamentalExist->save();
        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        $parameters['id'] = $fundamental->id;

        if ($expected_message == 'FundamentalEdit.name_unique') {
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

        $this->assertMessageError($type_message_error, $response, $permission, $expected_message);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     *
     * @return Array
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
                'expected_message' => 'This action is unauthorized.',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalEdit
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
                    'data' => $fundamentalEdit
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
                    'data' => $fundamentalEdit
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
                    'data' => $fundamentalEdit
                ],
                'permission' => true,
            ],
        ];
    }
}
