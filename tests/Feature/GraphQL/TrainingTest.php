<?php

namespace Tests\Feature\GraphQL;

use App\Models\ScoutFundamentalTraining;
use App\Models\ScoutsAttack;
use App\Models\ScoutsBlock;
use App\Models\ScoutsDefense;
use App\Models\ScoutsReception;
use App\Models\ScoutsServe;
use App\Models\ScoutsSetAssist;
use App\Models\Team;
use App\Models\TeamsUsers;
use App\Models\Training;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TrainingTest extends TestCase
{
    protected bool $graphql = true;

    protected bool $tenancy = true;

    protected bool $login = true;

    /**
     * @var string
     */
    public static $trainingText = ' TRAINING';

    private string $role = 'technician';

    /**
     * @var string
     */
    public static $formatDate = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    public static $dateStart = '2022-10-23 13:50:00';

    /**
     * @var string
     */
    public static $dateEnd = '2022-10-22 13:45:00';

    /**
     * @var string
     */
    public static $dateStartError = '08/10/2022 13:50:00';

    /**
     * @var string
     */
    public static $dateEndError = '08/10/2022 13:55:00';

    /**
     * @var string
     */
    public static $twoHours = ' +2 hours';

    /**
     * @var string
     */
    public static $treeHours = ' +3 hours';

    /**
     * @var string
     */
    public static $moreTwoDays = '+2 days';

    /**
     * @var array<int, string>
     */
    public static $data = [
        'id',
        'name',
        'userId',
        'teamId',
        'dateStart',
        'dateEnd',
        'status',
        'description',
        'createdAt',
        'updatedAt',
    ];

    private function setPermissions(bool $hasPermission): void
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-training');
        $this->checkPermission($hasPermission, $this->role, 'view-training');
    }

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
        Training::truncate();
        TeamsUsers::truncate();
        ScoutsAttack::truncate();
        ScoutsBlock::truncate();
        ScoutsDefense::truncate();
        ScoutsReception::truncate();
        ScoutsServe::truncate();
        ScoutsSetAssist::truncate();
        ScoutFundamentalTraining::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Listagem de todos os treinos.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function training_list(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->be(User::findOrFail(3));

        $this->setPermissions($hasPermission);

        $team = Team::factory()
            ->create();

        Training::factory()
            ->setTeamId($team->id)
            ->create();

        $response = $this->graphQL(
            'trainings',
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
                        'trainings' => [
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
    public function training_info(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->be(User::findOrFail(3));

        $this->setPermissions($hasPermission);

        $team = Team::factory()
            ->create();

        $training = Training::factory()
            ->setTeamId($team->id)
            ->create();

        $response = $this->graphQL(
            'training',
            [
                'id' => $training->id,
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
                        'training' => self::$data,
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
     * Método de criação de um treino.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('trainingCreateSuccessProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('trainingCreateErrorProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function training_create(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $this->be(User::findOrFail(3));

        $team = Team::factory()
            ->create();

        if ($parameters['sendEmailPlayer']) {
            $player = $team->players()->first();

            if ($player) {
                $setting = $player->notificationSettings()
                    ->whereHas('notificationType', function ($query) {
                        $query->where('key', 'training_created');
                    })
                    ->first();

                if ($setting) {
                    $setting->via_email = true;
                    $setting->save();
                }
            }
        }

        if ($parameters['sendEmailTechnician']) {
            $technician = $team->technicians()->first();

            if ($technician) {
                $setting = $technician->notificationSettings()
                    ->whereHas('notificationType', function ($query) {
                        $query->where('key', 'training_created');
                    })
                    ->first();

                if ($setting) {
                    $setting->via_email = true;
                    $setting->save();
                }
            }
        }

        $user = $team->players->firstOrFail();

        $user->roles()->sync(2);

        TeamsUsers::where('user_id', $user->id)->update(['role' => 'technician']);

        $team->save();

        $parameters['teamId'] = $team->id;

        unset($parameters['sendEmailPlayer']);
        unset($parameters['sendEmailTechnician']);

        $response = $this->graphQL(
            'trainingCreate',
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

        // TODO - Fiz essas validações, agora falta fazer o retorno dessas informações na API
        // TODO - Criei o relacionamento também

        $this->assertDatabaseCount('scout_fundamentals_training', 9);
        $this->assertDatabaseCount('scouts_attack', 9);
        $this->assertDatabaseCount('scouts_block', 9);
        $this->assertDatabaseCount('scouts_defense', 9);
        $this->assertDatabaseCount('scouts_reception', 9);
        $this->assertDatabaseCount('scouts_serve', 9);
        $this->assertDatabaseCount('scouts_set_assist', 9);
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function trainingCreateSuccessProvider(): array
    {
        $faker = Faker::create();
        $nameExistent = $faker->name . self::$trainingText;
        $trainingCreate = ['trainingCreate'];

        $dateStart = $faker
            ->dateTimeBetween('now', self::$moreTwoDays)
            ->format(self::$formatDate);

        $today = $faker
            ->dateTimeBetween('now')
            ->format(self::$formatDate);

        $todayPlusTwoHours = $faker
            ->dateTimeBetween('now', '+2 hours')
            ->format(self::$formatDate);

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . self::$twoHours, $dateStart . self::$treeHours)
            ->format(self::$formatDate);

        return [
            'create training without permission, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => false,
            ],
            'create training with minimal parameters and send email player, success' => [
                [
                    'name' => $nameExistent,
                    'description' => null,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => true,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create training with minimal parameters and send email technician, success' => [
                [
                    'name' => $nameExistent,
                    'description' => null,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create training with minimal parameters, success' => [
                [
                    'name' => $nameExistent,
                    'description' => null,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create training with full parameters, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create training with relationship fundamentals, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                    'fundamentalId' => [1, 2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create training with relationship specific fundamental, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [1, 2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create training with notification if training date is current day, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'dateStart' => $today,
                    'dateEnd' => $todayPlusTwoHours,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [1, 2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function trainingCreateErrorProvider(): array
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . self::$trainingText;
        $trainingCreate = ['trainingCreate'];

        $dateStart = $faker
            ->dateTimeBetween('now', self::$moreTwoDays)
            ->format(self::$formatDate);

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . self::$twoHours, $dateStart . self::$treeHours)
            ->format(self::$formatDate);

        return [
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TrainingCreate.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TrainingCreate.name_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => true,
            ],
            'dateStart must be less than dateEnd, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStart,
                    'dateEnd' => self::$dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => 'dateStart',
                'expectedMessage' => 'TrainingCreate.date_start_before',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => true,
            ],
            'dateEnd must be greater than dateStart, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStart,
                    'dateEnd' => self::$dateEnd,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => 'dateEnd',
                'expectedMessage' => 'TrainingCreate.date_end_after',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => true,
            ],
            'dateEnd without correct formatting, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStart,
                    'dateEnd' => '08/10/2022 13:45:00',
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => 'dateEnd',
                'expectedMessage' => 'TrainingCreate.date_end_date_format',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => true,
            ],
            'dateStart without correct formatting, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStartError,
                    'dateEnd' => self::$dateEndError,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                ],
                'typeMessageError' => 'dateStart',
                'expectedMessage' => 'TrainingCreate.date_start_date_format',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => true,
            ],
            'specific fundamentals unrelated to fundamentals on record, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStartError,
                    'dateEnd' => self::$dateEndError,
                    'sendEmailPlayer' => false,
                    'sendEmailTechnician' => false,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [13],
                ],
                'typeMessageError' => 'specificFundamentalId',
                'expectedMessage' => 'TrainingCreate.specific_fundamentals_not_relationship',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um treino.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('trainingEditSuccessProvider')]
    #[\PHPUnit\Framework\Attributes\DataProvider('trainingEditErrorProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function training_edit(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission,
        bool $cancellation
    ) {
        $this->setPermissions($hasPermission);

        $this->be(User::findOrFail(3));

        $team = Team::factory()->hasPlayers(10)->create();

        $status = isset($parameters['status']) ? (bool) $parameters['status'] : false;

        $training = Training::factory()
            ->setStatus($status)
            ->make([
                'team_id' => $team->id,
            ]);

        $training->save();

        $parameters['id'] = $training->id;

        $user = $team->players->firstOrFail();

        $user->roles()->sync(2);

        TeamsUsers::where('user_id', $user->id)->update(['role' => 'technician']);

        $team->save();

        $parameters['teamId'] = $team->id;

        if ($cancellation) {
            $parameters['status'] = false;
        }

        $response = $this->graphQL(
            'trainingEdit',
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function trainingEditSuccessProvider(): array
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . self::$trainingText;

        $dateStart = $faker
            ->dateTimeBetween('now', self::$moreTwoDays)
            ->format(self::$formatDate);

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . self::$twoHours, $dateStart . self::$treeHours)
            ->format(self::$formatDate);

        $today = $faker
            ->dateTimeBetween('now')
            ->format(self::$formatDate);

        $todayPlusTwoHours = $faker
            ->dateTimeBetween('now', '+2 hours')
            ->format(self::$formatDate);

        return [
            'edit training with minimal parameters, success' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'status' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'edit training with full parameters, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'status' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'edit training with relationship fundamentals, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'status' => true,
                    'fundamentalId' => [1, 2, 3],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'edit training with relationship specific fundamental, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'status' => true,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [1, 2, 3],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'edit training cancel, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'status' => true,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'cancellation' => true,
            ],
            'edit training reactivate, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'status' => true,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'edit training with notification if training date is current day, success' => [
                [
                    'name' => $nameExistent,
                    'description' => $faker->text,
                    'status' => true,
                    'dateStart' => $today,
                    'dateEnd' => $todayPlusTwoHours,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
        ];
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function trainingEditErrorProvider(): array
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . self::$trainingText;
        $trainingEdit = ['trainingEdit'];

        $dateStart = $faker
            ->dateTimeBetween('now', self::$moreTwoDays)
            ->format(self::$formatDate);

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . self::$twoHours, $dateStart . self::$treeHours)
            ->format(self::$formatDate);

        return [
            'edit training without permission, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'status' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingEdit,
                ],
                'hasPermission' => false,
                'cancellation' => false,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'status' => true,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TrainingCreate.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingEdit,
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'status' => true,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'TrainingCreate.name_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingEdit,
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'dateStart must be less than dateEnd, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStart,
                    'dateEnd' => self::$dateEnd,
                    'status' => true,
                ],
                'typeMessageError' => 'dateStart',
                'expectedMessage' => 'TrainingEdit.date_start_before',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingEdit,
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'dateEnd must be greater than dateStart, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStart,
                    'dateEnd' => self::$dateEnd,
                    'status' => true,
                ],
                'typeMessageError' => 'dateEnd',
                'expectedMessage' => 'TrainingEdit.date_end_after',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingEdit,
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'dateEnd without correct formatting, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStart,
                    'dateEnd' => '08/10/2022 13:45:00',
                    'status' => true,
                ],
                'typeMessageError' => 'dateEnd',
                'expectedMessage' => 'TrainingEdit.date_end_date_format',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingEdit,
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'dateStart without correct formatting, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStartError,
                    'dateEnd' => self::$dateEndError,
                    'status' => true,
                ],
                'typeMessageError' => 'dateStart',
                'expectedMessage' => 'TrainingEdit.date_start_date_format',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingEdit,
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
            'specific fundamentals unrelated to fundamentals on record, expected error' => [
                [
                    'name' => $nameExistent,
                    'dateStart' => self::$dateStartError,
                    'dateEnd' => self::$dateEndError,
                    'status' => true,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [13],
                ],
                'typeMessageError' => 'specificFundamentalId',
                'expectedMessage' => 'TrainingEdit.specific_fundamentals_not_relationship',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingEdit,
                ],
                'hasPermission' => true,
                'cancellation' => false,
            ],
        ];
    }

    /**
     * Método de exclusão de um treino.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('trainingDeleteProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function training_delete(
        array $data,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $team = Team::factory()->create();

        $training = Training::factory()
            ->setTeamId($team->id)
            ->create();

        /** @var array<string, mixed> $params */
        $params = $data;

        $params['id'] = $training->id;

        if ($data['error'] != null) {
            unset($params['error']);
            $params['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'trainingDelete',
            $params,
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
    public static function trainingDeleteProvider(): array
    {
        $trainingDelete = ['trainingDelete'];

        return [
            'delete training, success' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingDelete' => [self::$data],
                    ],
                ],
                'hasPermission' => true,
            ],
            'delete training without permission, expected error' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingDelete,
                ],
                'hasPermission' => false,
            ],
            'delete training that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'typeMessageError' => 'id',
                'expectedMessage' => 'The selected id is invalid.',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
