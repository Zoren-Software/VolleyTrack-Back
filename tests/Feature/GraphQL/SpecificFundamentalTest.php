<?php

namespace Tests\Feature\GraphQL;

use App\Models\Fundamental;
use App\Models\SpecificFundamental;
use Faker\Factory as Faker;
use Tests\TestCase;

class SpecificFundamentalTest extends TestCase
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
     * Listagem de todos os fundamentos especificos.
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @return void
     */
    public function specificFundamentalsList()
    {
        SpecificFundamental::factory()->make()->save();

        $this->graphQL(
            'specificFundamentals',
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
                'specificFundamentals' => [
                    'paginatorInfo' => $this->paginatorInfo,
                    'data' => [
                        '*' => $this->data,
                    ],
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Listagem de um fundamento especifico.
     *
     * @test
     *
     * @author Maicon Cerutti
     *
     * @dataProvider infoProvider
     *
     * @return void
     */
    public function specificFundamentalInfo()
    {
        $specificFundamental = SpecificFundamental::factory()->make();
        $specificFundamental->save();

        $this->graphQL(
            'specificFundamental',
            [
                'id' => $specificFundamental->id,
            ],
            $this->data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'specificFundamental' => $this->data,
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de criação de um fundamento especifico.
     *
     * @dataProvider specificFundamentalCreateProvider
     *
     * @test
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function specificFundamentalCreate(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission,
        $addRelationship
        ) {
        $this->checkPermission($permission, $this->permission, 'edit-specific-fundamental');

        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        if ($addRelationship) {
            $parameters['fundamentalId'] = $fundamental->id;
        }

        $response = $this->graphQL(
            'specificFundamentalCreate',
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
    public function specificFundamentalCreateProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name;
        $specificFundamentalCreate = ['specificFundamentalCreate'];

        return [
            'create specific fundamental, with relationship, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalCreate' => $this->data,
                    ],
                ],
                'permission' => true,
                'add_relationship' => true,
            ],
            'create specific fundamental, no relationship, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalCreate' => $this->data,
                    ],
                ],
                'permission' => true,
                'add_relationship' => false,
            ],
            'create specific fundamental without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $specificFundamentalCreate,
                ],
                'permission' => false,
                'add_relationship' => false,
            ],
            'name field is not unique, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'SpecificFundamentalCreate.name_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $specificFundamentalCreate,
                ],
                'permission' => true,
                'add_relationship' => false,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'SpecificFundamentalCreate.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $specificFundamentalCreate,
                ],
                'permission' => true,
                'add_relationship' => false,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'SpecificFundamentalCreate.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $specificFundamentalCreate,
                ],
                'permission' => true,
                'add_relationship' => false,
            ],
        ];
    }

    /**
     * Método de edição de um fundamento especifico.
     *
     * @dataProvider specificFundamentalEditProvider
     *
     * @test
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function specificFundamentalEdit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission,
        $addRelationship
        ) {
        $this->checkPermission($permission, $this->permission, 'edit-specific-fundamental');

        $specificFundamentalExist = SpecificFundamental::factory()->make();
        $specificFundamentalExist->save();
        $specificFundamental = SpecificFundamental::factory()->make();
        $specificFundamental->save();

        $parameters['id'] = $specificFundamental->id;

        if ($expectedMessage == 'SpecificFundamentalEdit.name_unique') {
            $parameters['name'] = $specificFundamentalExist->name;
        }

        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        if ($addRelationship) {
            $parameters['fundamentalId'] = $fundamental->id;
        }

        $response = $this->graphQL(
            'specificFundamentalEdit',
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
    public function specificFundamentalEditProvider()
    {
        $faker = Faker::create();
        $userId = 2;
        $fundamentalEdit = ['specificFundamentalEdit'];

        return [
            'edit specific fundamental without permission, expected error' => [
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
                'add_relationship' => false,
            ],
            'edit specific fundamental, no relationship, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalEdit' => $this->data,
                    ],
                ],
                'permission' => true,
                'add_relationship' => false,
            ],
            'edit specific fundamental, with relationship, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalEdit' => $this->data,
                    ],
                ],
                'permission' => true,
                'add_relationship' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'SpecificFundamentalEdit.name_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalEdit,
                ],
                'permission' => true,
                'add_relationship' => false,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'SpecificFundamentalEdit.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalEdit,
                ],
                'permission' => true,
                'add_relationship' => false,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'SpecificFundamentalEdit.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalEdit,
                ],
                'permission' => true,
                'add_relationship' => false,
            ],
        ];
    }

    /**
     * Método de exclusão de um time.
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @dataProvider specificFundamentalDeleteProvider
     *
     * @return void
     */
    public function specificFundamentalDelete($data, $typeMessageError, $expectedMessage, $expected, $permission)
    {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'edit-specific-fundamental');

        $specificFundamental = SpecificFundamental::factory()->make();
        $specificFundamental->save();

        $parameters['id'] = $specificFundamental->id;

        if ($data['error'] != null) {
            $parameters['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'specificFundamentalDelete',
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
     * @return void
     */
    public function specificFundamentalDeleteProvider()
    {
        $specificFundamentalDelete = ['specificFundamentalDelete'];

        return [
            'delete specific fundamental, success' => [
                [
                    'error' => null,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalDelete' => [$this->data],
                    ],
                ],
                'permission' => true,
            ],
            'delete specific fundamental without permission, expected error' => [
                [
                    'error' => null,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $specificFundamentalDelete,
                ],
                'permission' => false,
            ],
            'delete specific fundamental that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'type_message_error' => 'message',
                'expected_message' => 'internal',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $specificFundamentalDelete,
                ],
                'permission' => true,
            ],
        ];
    }
}
