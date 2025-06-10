<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
use App\Models\TeamCategory;
use App\Models\TeamLevel;
use App\Models\TeamsUsers;
use Database\Seeders\Tenants\TeamCategoryTableSeeder;
use Database\Seeders\Tenants\TeamLevelTableSeeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TeamTest extends TestCase
{
    protected bool $graphql = true;

    protected bool $tenancy = true;

    protected bool $login = true;

    /**
     * @var string
     */
    public static $teamText = ' TEAM';

    private string $role = 'technician';

    /**
     * @var array<int, string>
     */
    public static $data = [
        'id',
        'name',
        'userId',
        'teamCategoryId',
        'teamLevelId',
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

        Team::truncate();
        TeamsUsers::truncate();
        TeamCategory::truncate();
        TeamLevel::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seed([
            TeamCategoryTableSeeder::class,
            TeamLevelTableSeeder::class,
        ]);
    }

    /**
     * @return void
     */
    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-team');
        $this->checkPermission($hasPermission, $this->role, 'view-team');
    }

    /**
     * Listagem de todos os times.
     *
     * @param  string|bool  $typeMessageError
     * @param  string|bool  $expectedMessage
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function teams_list(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $teamCategory = TeamCategory::where('id', 1)->firstOrFail();

        $teamLevel = TeamLevel::where('id', 1)->firstOrFail();

        Team::factory()
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
     * @return array<string, array<string, mixed>>
     */
    public static function listProvider(): array
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
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('infoProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function team_info(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $teamCategory = TeamCategory::where('id', 1)->firstOrFail();

        $teamLevel = TeamLevel::where('id', 1)->firstOrFail();

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
     * @return array<string, array<string, mixed>>
     */
    public static function infoProvider(): array
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
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @param  bool  $hasPermission
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('teamCreateProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function team_create(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        if (isset($parameters['teamCategoryId'])) {
            if ($parameters['teamCategoryId'] == true) {
                $teamCategory = TeamCategory::factory()->create();
                $parameters['teamCategoryId'] = $teamCategory->id;
            }
        }

        if (isset($parameters['teamLevelId'])) {
            if ($parameters['teamLevelId'] == true) {
                $teamLevel = TeamLevel::factory()->create();
                $parameters['teamLevelId'] = $teamLevel->id;
            }
        }

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

        if (!empty($parameters['playerId']) && is_array($parameters['playerId'])) {
            foreach ($parameters['playerId'] as $playerId) {
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function teamCreateProvider(): array
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
            'create team and relating a team category and team level, success' => [
                [
                    'name' => $faker->name,
                    'teamCategoryId' => true,
                    'teamLevelId' => true,
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
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @param  bool  $hasPermission
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('teamEditProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function team_edit(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $teamExist = Team::factory()->make();
        $teamExist->save();
        $team = Team::factory()->make();
        $team->save();

        $parameters['id'] = $team->id;

        if (isset($parameters['teamCategoryId'])) {
            if ($parameters['teamCategoryId'] == true) {
                $teamCategory = TeamCategory::factory()->create();
                $parameters['teamCategoryId'] = $teamCategory->id;
            }
        }

        if (isset($parameters['teamLevelId'])) {
            if ($parameters['teamLevelId'] == true) {
                $teamLevel = TeamLevel::factory()->create();
                $parameters['teamLevelId'] = $teamLevel->id;
            }
        }

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

        if (!empty($parameters['playerId']) && is_array($parameters['playerId'])) {
            foreach ($parameters['playerId'] as $playerId) {
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function teamEditProvider(): array
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
            'edit team and relating a team category and team level, success' => [
                [
                    'name' => $faker->name,
                    'teamCategoryId' => true,
                    'teamLevelId' => true,
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
     * @param  array<string, mixed>  $data
     * @param  string|bool  $typeMessageError
     * @param  string|bool  $expectedMessage
     * @param  array<string, mixed>  $expected
     * @param  bool  $hasPermission
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('teamDeleteProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function team_delete($data, $typeMessageError, $expectedMessage, $expected, $hasPermission)
    {
        $this->setPermissions($hasPermission);

        $team = Team::factory()->make();
        $team->save();

        /** @var array<string, mixed> $parameters */
        $parameters = [
            'id' => $team->id,
        ];

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
     * @return array<string, array<int|string, mixed>>
     */
    public static function teamDeleteProvider(): array
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
