<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
use App\Models\TeamsUsers;
use App\Models\Training;
use App\Models\User;
use Faker\Factory as Faker;
use Tests\TestCase;

class TrainingTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    public static $trainingText = ' TRAINING';

    private $role = 'technician';

    public static $dateStart = '2022-10-23 13:50:00';

    public static $dateEnd = '2022-10-22 13:45:00';

    public static $dateStartError = '08/10/2022 13:50:00';

    public static $dateEndError = '08/10/2022 13:55:00';

    public static $twoHours = ' +2 hours';

    public static $treeHours = ' +3 hours';

    public static $moreTwoDays = '+2 days';

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

    /**
     * Listagem de todos os treinos.
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @dataProvider listProvider
     *
     * @return void
     */
    public function trainingList(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->be(User::find(3));

        $this->setPermissions($hasPermission);

        Training::factory()->make()->save();

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
     * @test
     *
     * @dataProvider infoProvider
     *
     * @return void
     */
    public function trainingInfo(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->be(User::find(3));

        $this->setPermissions($hasPermission);

        $training = Training::factory()->make();
        $training->save();

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
     * @dataProvider trainingCreateSuccessProvider
     * @dataProvider trainingCreateErrorProvider
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @return void
     */
    public function trainingCreate(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $this->be(User::find(3));

        $team = Team::factory()
            ->hasPlayers(10)
            ->create();

        $user = $team->players->first();

        $user->roles()->sync(2);

        TeamsUsers::where('user_id', $user->id)->update(['role' => 'technician']);

        $team->save();

        $parameters['teamId'] = $team->id;

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
    }

    /**
     * @return array
     */
    public static function trainingCreateSuccessProvider()
    {
        $faker = Faker::create();
        $userId = 1;
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
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingCreate,
                ],
                'hasPermission' => false,
            ],
            'create training with minimal parameters, success' => [
                [
                    'name' => $nameExistent,
                    'description' => null,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
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
     * @return array
     */
    public static function trainingCreateErrorProvider()
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
     * @dataProvider trainingEditSuccessProvider
     * @dataProvider trainingEditErrorProvider
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @return void
     */
    public function trainingEdit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission,
        bool $cancellation
    ) {
        $this->setPermissions($hasPermission);

        $this->be(User::find(3));

        $training = Training::factory()
            ->setStatus($parameters['status'])
            ->make();
        $training->save();

        $parameters['id'] = $training->id;

        $team = Team::factory()
            ->hasPlayers(10)
            ->create();

        $user = $team->players->first();

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
     * @return array
     */
    public static function trainingEditSuccessProvider()
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
     * @return array
     */
    public static function trainingEditErrorProvider()
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
     * @dataProvider trainingDeleteProvider
     *
     * @return void
     */
    public function trainingDelete(
        $data,
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $training = Training::factory()->make();
        $training->save();

        $parameters['id'] = $training->id;

        if ($data['error'] != null) {
            $parameters['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'trainingDelete',
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
    public static function trainingDeleteProvider()
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
                'typeMessageError' => 'message',
                'expectedMessage' => 'internal',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
