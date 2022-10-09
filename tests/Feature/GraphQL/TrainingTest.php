<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
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
     * @dataProvider trainingCreateProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_training_create(
        $parameters,
        $type_message_error,
        $expected_message,
        $expected,
        $permission
    ) {
        $this->checkPermission(
            $permission,
            $this->permission,
            'create-training'
        );

        $team = Team::factory()->make();
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
            $type_message_error,
            $response,
            $permission,
            $expected_message
        );

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function trainingCreateProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . $this->trainingText;
        $trainingCreate = ['trainingCreate'];

        $dateStart = $faker
            ->dateTimeBetween('now', '+2 days')
            ->format('Y-m-d H:i:s');

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . ' +2 hours', $dateStart . ' +3 hours')
            ->format('Y-m-d H:i:s');

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
                    'dateStart' => '2022-10-23 13:50:00',
                    'dateEnd' => '2022-10-22 13:45:00',
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
                    'dateStart' => '2022-10-23 13:50:00',
                    'dateEnd' => '2022-10-22 13:45:00',
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
                    'dateStart' => '2022-10-23 13:50:00',
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
                    'dateStart' => '08/10/2022 13:50:00',
                    'dateEnd' => '2022-10-23 13:45:00',
                ],
                'type_message_error' => 'dateStart',
                'expected_message' => 'TrainingCreate.date_start_date_format',
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
     * @dataProvider trainingEditProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_training_edit(
        $parameters,
        $type_message_error,
        $expected_message,
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

        $team = Team::factory()->make();
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
            $type_message_error,
            $response,
            $permission,
            $expected_message
        );

        // dd($response);
        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public function trainingEditProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . $this->trainingText;
        $trainingEdit = ['trainingEdit'];

        $dateStart = $faker
            ->dateTimeBetween('now', '+2 days')
            ->format('Y-m-d H:i:s');

        $dateEnd = $faker
            ->dateTimeBetween($dateStart . ' +2 hours', $dateStart . ' +3 hours')
            ->format('Y-m-d H:i:s');

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
                    'dateStart' => '2022-10-23 13:50:00',
                    'dateEnd' => '2022-10-22 13:45:00',
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
                    'dateStart' => '2022-10-23 13:50:00',
                    'dateEnd' => '2022-10-22 13:45:00',
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
                    'dateStart' => '2022-10-23 13:50:00',
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
                    'dateStart' => '08/10/2022 13:50:00',
                    'dateEnd' => '2022-10-23 13:45:00',
                ],
                'type_message_error' => 'dateStart',
                'expected_message' => 'TrainingEdit.date_start_date_format',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingEdit,
                ],
                'permission' => true,
            ],
        ];
    }

    // TODO - Funções de delete
    // TODO - Funções relacionar fundamentos
    // TODO - Funções relacionar fundamentos específicos

        /**
     * Método de exclusão de um treino.
     *
     * @author Maicon Cerutti
     *
     * @dataProvider trainingDeleteProvider
     *
     * @return void
     */
    public function test_training_delete($data, $type_message_error, $expected_message, $expected, $permission)
    {
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
