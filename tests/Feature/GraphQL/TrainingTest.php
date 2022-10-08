<?php

namespace Tests\Feature\GraphQL;

use App\Models\Training;
use App\Models\Team;
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
    public function test_training_create($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->checkPermission($permission, $this->permission, 'create-training');

        // create factory team
        //dd($parameters['teamId'] == null);
        if (!isset($parameters['teamId'])) {
            $team = Team::factory()->make();
            $team->save();
            $parameters['teamId'] = $team->id;
        }

        $response = $this->graphQL(
            'trainingCreate',
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
     * TODO - Fazendo testes funcionais para criação de treinos.
     * @return array
     */
    public function trainingCreateProvider()
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . $this->trainingText;
        $trainingCreate = ['trainingCreate'];

        $date = $faker->dateTimeBetween('now', '+2 days')->format('Y-m-d');

        return [
            'create training without permission, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'date' => $date,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => false,
            ],
            'create training, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                    'date' => $date,
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
            // 'create training and relating a players, success' => [
            //     [
            //         'name' => $faker->name,
            //         'userId' => $userId,
            //         'teamId' => [1, 2, 3],
            //     ],
            //     'type_message_error' => false,
            //     'expected_message' => false,
            //     'expected' => [
            //         'data' => [
            //             'teamCreate' => $this->data,
            //         ],
            //     ],
            //     'permission' => true,
            // ],

            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                    'date' => $date,
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
                    'date' => $date,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TrainingCreate.name_min',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
            ],
            'teamId is required, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                    'teamId' => null,
                    'date' => $date,
                ],
                'type_message_error' => 'team',
                'expected_message' => 'TrainingCreate.team_id_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingCreate,
                ],
                'permission' => true,
            ],
        ];
    }
}
