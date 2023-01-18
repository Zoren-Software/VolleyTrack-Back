<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
use App\Models\TeamsUsers;
use App\Models\Training;
use Faker\Factory as Faker;
use Tests\TestCase;

class TrainingTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $trainingText = ' TRAINING';

    private $permission = 'Técnico';

    private $dateStart = '2022-10-23 13:50:00';

    private $dateEnd = '2022-10-22 13:45:00';

    private $dateStartError = '08/10/2022 13:50:00';

    private $dateEndError = '08/10/2022 13:55:00';

    private $twoHours = ' +2 hours';

    private $treeHours = ' +3 hours';

    private $moreTwoDays = '+2 days';

    private $data = [
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

    /**
     * Listagem de todos os treinos.
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_training_list()
    {
        Training::factory()->make()->save();

        $this->graphQL(
            'trainings',
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
                'trainings' => [
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
     * @return void
     */
    public function test_training_info()
    {
        $training = Training::factory()->make();
        $training->save();

        $this->graphQL(
            'training',
            [
                'id' => $training->id,
            ],
            $this->data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'training' => $this->data,
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de criação de um treino.
     *
     * @dataProvider trainingCreateSuccessProvider
     * @dataProvider trainingCreateErrorProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_training_create(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission
    ) {
        $this->checkPermission(
            $permission,
            $this->permission,
            'create-training'
        );

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
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $permission,
            $expectedMessage
        );

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function trainingCreateSuccessProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . $this->trainingText;
        $trainingCreate = ['trainingCreate'];

        $dateStart = $faker
            ->dateTimeBetween('now', $this->moreTwoDays)
            ->format($this->formatDate);

        $today = $faker
            ->dateTimeBetween('now')
            ->format($this->formatDate);

        $todayPlusTwoHours = $faker
            ->dateTimeBetween('now', '+2 hours')
            ->format($this->formatDate);

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . $this->twoHours, $dateStart . $this->treeHours)
            ->format($this->formatDate);

        return [
            'create training without permission, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => false,
            ],
            'create training with minimal parameters, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => null,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create training with full parameters, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create training with relationship fundamentals, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'fundamentalId' => [1, 2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create training with relationship specific fundamental, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [1, 2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create training with notification if training date is current day, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'dateStart' => $today,
                    'dateEnd' => $todayPlusTwoHours,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [1, 2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function trainingCreateErrorProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . $this->trainingText;
        $trainingCreate = ['trainingCreate'];

        $dateStart = $faker
            ->dateTimeBetween('now', $this->moreTwoDays)
            ->format($this->formatDate);

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . $this->twoHours, $dateStart . $this->treeHours)
            ->format($this->formatDate);

        return [
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TrainingCreate.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TrainingCreate.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
            ],
            'DateStart must be less than dateEnd, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStart,
                    'dateEnd' => $this->dateEnd,
                ],
                'type_message_error' => 'dateStart',
                'expected_message' => 'TrainingCreate.date_start_before',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
            ],
            'DateEnd must be greater than dateStart, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStart,
                    'dateEnd' => $this->dateEnd,
                ],
                'type_message_error' => 'dateEnd',
                'expected_message' => 'TrainingCreate.date_end_after',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
            ],
            'DateEnd without correct formatting, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStart,
                    'dateEnd' => '08/10/2022 13:45:00',
                ],
                'type_message_error' => 'dateEnd',
                'expected_message' => 'TrainingCreate.date_end_date_format',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
            ],
            'DateStart without correct formatting, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStartError,
                    'dateEnd' => $this->dateEndError,
                ],
                'type_message_error' => 'dateStart',
                'expected_message' => 'TrainingCreate.date_start_date_format',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
            ],
            'specific fundamentals unrelated to fundamentals on record, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStartError,
                    'dateEnd' => $this->dateEndError,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [13],
                ],
                'type_message_error' => 'specificFundamentalId',
                'expected_message' => 'TrainingCreate.specific_fundamentals_not_relationship',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
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
     * @return void
     */
    public function test_training_edit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission
    ) {
        $this->checkPermission(
            $permission,
            $this->permission,
            'edit-training'
        );

        $training = Training::factory()->make();
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

        $response = $this->graphQL(
            'trainingEdit',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $permission,
            $expectedMessage
        );

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function trainingEditSuccessProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . $this->trainingText;

        $dateStart = $faker
            ->dateTimeBetween('now', $this->moreTwoDays)
            ->format($this->formatDate);

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . $this->twoHours, $dateStart . $this->treeHours)
            ->format($this->formatDate);

        $today = $faker
            ->dateTimeBetween('now')
            ->format($this->formatDate);

        $todayPlusTwoHours = $faker
            ->dateTimeBetween('now', '+2 hours')
            ->format($this->formatDate);

        return [
            'edit training with minimal parameters, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit training with full parameters, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit training with relationship fundamentals, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'fundamentalId' => [1, 2, 3],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit training with relationship specific fundamental, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [1, 2, 3],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit training cancel, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'status' => false,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit training reactivate, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'status' => true,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit training with notification if training date is current day, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'description' => $faker->text,
                    'status' => true,
                    'dateStart' => $today,
                    'dateEnd' => $todayPlusTwoHours,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function trainingEditErrorProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . $this->trainingText;
        $trainingEdit = ['trainingEdit'];

        $dateStart = $faker
            ->dateTimeBetween('now', $this->moreTwoDays)
            ->format($this->formatDate);

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . $this->twoHours, $dateStart . $this->treeHours)
            ->format($this->formatDate);

        return [
            'edit training without permission, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => false,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TrainingCreate.name_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TrainingCreate.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => true,
            ],
            'DateStart must be less than dateEnd, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStart,
                    'dateEnd' => $this->dateEnd,
                ],
                'type_message_error' => 'dateStart',
                'expected_message' => 'TrainingEdit.date_start_before',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => true,
            ],
            'DateEnd must be greater than dateStart, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStart,
                    'dateEnd' => $this->dateEnd,
                ],
                'type_message_error' => 'dateEnd',
                'expected_message' => 'TrainingEdit.date_end_after',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => true,
            ],
            'DateEnd without correct formatting, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStart,
                    'dateEnd' => '08/10/2022 13:45:00',
                ],
                'type_message_error' => 'dateEnd',
                'expected_message' => 'TrainingEdit.date_end_date_format',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => true,
            ],
            'DateStart without correct formatting, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStartError,
                    'dateEnd' => $this->dateEndError,
                ],
                'type_message_error' => 'dateStart',
                'expected_message' => 'TrainingEdit.date_start_date_format',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => true,
            ],
            'specific fundamentals unrelated to fundamentals on record, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'dateStart' => $this->dateStartError,
                    'dateEnd' => $this->dateEndError,
                    'fundamentalId' => [1],
                    'specificFundamentalId' => [13],
                ],
                'type_message_error' => 'specificFundamentalId',
                'expected_message' => 'TrainingEdit.specific_fundamentals_not_relationship',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => true,
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
    public function test_training_delete(
        $data,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission
    ) {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'delete-training');

        $training = Training::factory()->make();
        $training->save();

        $parameters['id'] = $training->id;

        if ($data['error'] != null) {
            $parameters['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'trainingDelete',
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
    public function trainingDeleteProvider()
    {
        $trainingDelete = ['trainingDelete'];

        return [
            'delete training, success' => [
                [
                    'error' => null,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingDelete' => [$this->data],
                    ],
                ],
                'permission' => true,
            ],
            'delete training without permission, expected error' => [
                [
                    'error' => null,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingDelete,
                ],
                'permission' => false,
            ],
            'delete training that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'type_message_error' => 'message',
                'expected_message' => 'internal',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingDelete,
                ],
                'permission' => true,
            ],
        ];
    }
}
