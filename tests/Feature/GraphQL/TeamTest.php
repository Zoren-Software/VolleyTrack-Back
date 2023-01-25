<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
use Faker\Factory as Faker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $teamText = ' TEAM';

    private $role = 'technician';

    private $data = [
        'id',
        'name',
        'userId',
        'createdAt',
        'updatedAt',
    ];

    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-team');
        $this->checkPermission($hasPermission, $this->role, 'view-team');
    }

    /**
     * Listagem de todos os times.
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @dataProvider listProvider
     *
     * @return void
     */
    public function teamsList(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        Team::factory()->make()->save();

        $response = $this->graphQL(
            'teams',
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
    public function listProvider()
    {
        return [
            'with permission' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'teams' => [
                            'paginatorInfo' => $this->paginatorInfo,
                            'data' => [
                                '*' => $this->data,
                            ],
                        ],
                    ],
                ],
                'hasPermission' => true,
            ],
            'without permission' => [
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                ],
                'hasPermission' => false,
            ],
        ];
    }

    /**
     * Listagem de um time
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @dataProvider infoProvider
     *
     * @return void
     */
    public function teamInfo(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $team = Team::factory()->make();
        $team->save();

        $response = $this->graphQL(
            'team',
            [
                'id' => $team->id,
            ],
            $this->data,
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
            $response->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @return array
     */
    public function infoProvider()
    {
        return [
            'with permission' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'team' => $this->data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'without permission' => [
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                ],
                'hasPermission' => false,
            ],
        ];
    }

    /**
     * Método de criação de um time.
     *
     * @dataProvider teamCreateProvider
     *
     * @test
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function teamCreate(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $hasPermission
        ) {
        $this->setPermissions($hasPermission);

        $response = $this->graphQL(
            'teamCreate',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function teamCreateProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . $this->teamText;
        $teamCreate = ['teamCreate'];

        return [
            'create team without permission, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'playerId' => [],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamCreate,
                ],
                'hasPermission' => false,
            ],
            'create team, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'playerId' => [],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'teamCreate' => $this->data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create team and relating a players, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                    'playerId' => [1, 2, 3, 4, 5],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'teamCreate' => $this->data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'playerId' => [],
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TeamCreate.name_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                    'playerId' => [],
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TeamCreate.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                    'playerId' => [],
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TeamCreate.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamCreate,
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um time.
     *
     * @dataProvider teamEditProvider
     *
     * @test
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function teamEdit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $hasPermission
        ) {
        $this->setPermissions($hasPermission);

        $teamExist = Team::factory()->make();
        $teamExist->save();
        $team = Team::factory()->make();
        $team->save();

        $parameters['id'] = $team->id;

        if ($expectedMessage == 'TeamEdit.name_unique') {
            $parameters['name'] = $teamExist->name;
        }

        $response = $this->graphQL(
            'teamEdit',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function teamEditProvider()
    {
        $faker = Faker::create();
        $userId = 2;
        $teamEdit = ['teamEdit'];

        return [
            'edit team without permission, expected error' => [
                [
                    'name' => $faker->name . $this->teamText,
                    'userId' => $userId,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamEdit,
                ],
                'hasPermission' => false,
            ],
            'edit team, success' => [
                [
                    'name' => $faker->name . $this->teamText,
                    'userId' => $userId,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'teamEdit' => $this->data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'edit team and relating a players, success' => [
                [
                    'name' => $faker->name . $this->teamText,
                    'userId' => $userId,
                    'playerId' => [1, 2, 3],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'teamEdit' => $this->data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TeamEdit.name_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamEdit,
                ],
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TeamEdit.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamEdit,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TeamEdit.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamEdit,
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * Método de exclusão de um time.
     *
     * @author Maicon Cerutti
     *
     * @dataProvider teamDeleteProvider
     *
     * @test
     *
     * @return void
     */
    public function teamDelete($data, $typeMessageError, $expectedMessage, $expected, $hasPermission)
    {
        $this->setPermissions($hasPermission);

        $team = Team::factory()->make();
        $team->save();

        $parameters['id'] = $team->id;

        if ($data['error'] != null) {
            $parameters['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'teamDelete',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @author Maicon Cerutti
     *
     * @return array
     */
    public function teamDeleteProvider()
    {
        $teamDelete = ['teamDelete'];

        return [
            'delete team, success' => [
                [
                    'error' => null,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'teamDelete' => [$this->data],
                    ],
                ],
                'hasPermission' => true,
            ],
            'delete team without permission, expected error' => [
                [
                    'error' => null,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamDelete,
                ],
                'hasPermission' => false,
            ],
            'delete team that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'type_message_error' => 'message',
                'expected_message' => 'internal',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $teamDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
