<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
use App\Models\TeamCategory;
use App\Models\TeamLevel;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TeamTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    public static $teamText = ' TEAM';

    private $role = 'technician';

    public static $data = [
        'id',
        'name',
        'userId',
        // TODO - Parei aqui fazendo relacionamento para trazer entidades
        'teamCategoryId',
        'teamLevelId',
        'createdAt',
        'updatedAt',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->limparAmbiente();
    }

    public function tearDown(): void
    {
        $this->limparAmbiente();

        parent::tearDown();
    }

    private function limparAmbiente(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Team::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

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

        $teamCategory = TeamCategory::where('id', 1)->first();

        $teamLevel = TeamLevel::where('id', 1)->first();

        $team = Team::factory()
            ->hasPlayers(10)
            ->setAttributes([
                'team_category_id' => $teamCategory->id,
                'team_level_id' => $teamLevel->id,
            ])
            ->create();

        $response = $this->graphQL(
            'teams',
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
    public static function listProvider()
    {
        return [
            'with permission' => [
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'teams' => [
                            'paginatorInfo' => self::$paginatorInfo,
                            'data' => [
                                '*' => self::$data,
                            ],
                        ],
                    ],
                ],
                'hasPermission' => true,
            ],
            'without permission' => [
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
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

        $teamCategory = TeamCategory::where('id', 1)->first();

        $teamLevel = TeamLevel::where('id', 1)->first();

        $team = Team::factory()
            ->hasPlayers(10)
            ->setAttributes([
                'team_category_id' => $teamCategory->id,
                'team_level_id' => $teamLevel->id,
            ])
            ->create();

        $response = $this->graphQL(
            'team',
            [
                'id' => $team->id,
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
            $response->assertJsonStructure($expected)
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
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'team' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'without permission' => [
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
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

        if ($parameters['name'] == 'nameExistent') {
            $team = Team::factory()->create();
            $parameters['name'] = $team->name;
        }

        $response = $this->graphQL(
            'teamCreate',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        if (!empty($parameters['playerId'])) {
            foreach ($parameters['playerId'] as $playerId) {
                // Verifica na tabela teams_users se o relacionamento com cada jogador foi criado
                $this->assertDatabaseHas('teams_users', [
                    'team_id' => $response->json('data.teamCreate.id'),
                    'user_id' => $playerId,
                ]);
            }
        }

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public static function teamCreateProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . self::$teamText;
        $teamCreate = ['teamCreate'];

        return [
            'create team without permission, expected error' => [
                [
                    'name' => $nameExistent,
                    'playerId' => [],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $teamCreate,
                ],
                'hasPermission' => false,
            ],
            'create team, success' => [
                [
                    'name' => $nameExistent,
                    'playerId' => [],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'teamCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create team and relating a players, success' => [
                [
                    'name' => $faker->name,
                    'playerId' => [1, 2, 3, 4, 5],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'teamCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'name' => 'nameExistent',
                    'playerId' => [],
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TeamCreate.name_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $teamCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'playerId' => [],
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TeamCreate.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $teamCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'playerId' => [],
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TeamCreate.name_min',
                'expected' => [
                    'errors' => self::$errors,
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
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        if (!empty($parameters['playerId'])) {
            foreach ($parameters['playerId'] as $playerId) {
                // Verifica na tabela teams_users se o relacionamento com cada jogador foi criado
                $this->assertDatabaseHas('teams_users', [
                    'team_id' => $response->json('data.teamEdit.id'),
                    'user_id' => $playerId,
                ]);
            }
        }

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public static function teamEditProvider()
    {
        $faker = Faker::create();
        $userId = 2;
        $teamEdit = ['teamEdit'];

        return [
            'edit team without permission, expected error' => [
                [
                    'name' => $faker->name . self::$teamText,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $teamEdit,
                ],
                'hasPermission' => false,
            ],
            'edit team, success' => [
                [
                    'name' => $faker->name . self::$teamText,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'teamEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'edit team and relating a players, success' => [
                [
                    'name' => $faker->name . self::$teamText,
                    'playerId' => [1, 2, 3],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'teamEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'name field is not unique, expected error' => [
                [
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TeamEdit.name_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $teamEdit,
                ],
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TeamEdit.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $teamEdit,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TeamEdit.name_min',
                'expected' => [
                    'errors' => self::$errors,
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
            self::$data,
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
    public static function teamDeleteProvider()
    {
        $teamDelete = ['teamDelete'];

        return [
            'delete team, success' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'teamDelete' => [self::$data],
                    ],
                ],
                'hasPermission' => true,
            ],
            'delete team without permission, expected error' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $teamDelete,
                ],
                'hasPermission' => false,
            ],
            'delete team that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => 'internal',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $teamDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
