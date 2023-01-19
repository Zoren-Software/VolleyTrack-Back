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

    private $permission = 'Técnico';

    private $data = [
        'id',
        'name',
        'userId',
        'createdAt',
        'updatedAt',
    ];

    /**
     * Listagem de todos os times.
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @return void
     */
    public function teamsList()
    {
        Team::factory()->make()->save();

        $this->graphQL(
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
        )->assertJsonStructure([
            'data' => [
                'teams' => [
                    'paginatorInfo' => $this->paginatorInfo,
                    'data' => [
                        '*' => $this->data,
                    ],
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Listagem de um time
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @return void
     */
    public function teamInfo()
    {
        $team = Team::factory()->make();
        $team->save();

        $this->graphQL(
            'team',
            [
                'id' => $team->id,
            ],
            $this->data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'team' => $this->data,
            ],
        ])->assertStatus(200);
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
    public function teamCreate($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->checkPermission($permission, $this->permission, 'create-team');

        $response = $this->graphQL(
            'teamCreate',
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
                'permission' => false,
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
                'permission' => true,
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
                'permission' => true,
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
                'permission' => true,
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
                'permission' => true,
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
                'permission' => true,
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
    public function teamEdit($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->checkPermission($permission, $this->permission, 'edit-team');

        $teamExist = Team::factory()->make();
        $teamExist->save();
        $team = Team::factory()->make();
        $team->save();

        $parameters['id'] = $team->id;

        if ($expected_message == 'TeamEdit.name_unique') {
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

        $this->assertMessageError($type_message_error, $response, $permission, $expected_message);

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
                'permission' => false,
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
                'permission' => true,
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
                'permission' => true,
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
                'permission' => true,
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
                'permission' => true,
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
                'permission' => true,
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
    public function teamDelete($data, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'delete-team');

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

        $this->assertMessageError($type_message_error, $response, $permission, $expected_message);

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
                'permission' => true,
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
                'permission' => false,
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
                'permission' => true,
            ],
        ];
    }
}
