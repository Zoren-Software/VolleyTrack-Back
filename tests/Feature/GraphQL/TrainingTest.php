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
                    'data' => $trainingEdit,
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
                        'trainingEdit' => $this->data,
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
        ];
    }
}
