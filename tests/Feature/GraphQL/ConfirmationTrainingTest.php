<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
use App\Models\Training;
use Tests\TestCase;

class ConfirmationTrainingTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $role = 'technician';

    private $data = [
        'id',
        'userId',
        'playerId',
        'trainingId',
        'status',
        'teamId',
        'createdAt',
        'updatedAt',
    ];

    /**
     * Método de exclusão de um fundamento.
     *
     * @author Maicon Cerutti
     *
     * @dataProvider confirmTrainingProvider
     *
     * @test
     *
     * @return void
     */
    public function confirmTraining(
        $data,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $hasPermission
    ) {
        $team = Team::factory()
        ->hasPlayers(10)
        ->create();

        $this->be($team->user);

        $training = Training::factory()
            ->setTeamId($team->id)
            ->create();

        if ($data['error'] === null) {
            $confirmationTraining = $training->confirmationsTraining->first();
            $parameters = [
                'id' => $confirmationTraining->id,
                'trainingId' => $confirmationTraining->training_id,
                'playerId' => $confirmationTraining->player_id,
                'status' => [
                    'type' => 'ENUM',
                    'value' => 'CONFIRMED',
                ],
            ];
        } else {
            $parameters = $data['data_error'];
        }

        $response = $this->graphQL(
            'confirmTraining',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        if ($data['error'] === null) {
            $response
                ->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @author Maicon Cerutti
     *
     * @return array
     */
    public function confirmTrainingProvider()
    {
        $fundamentalDelete = ['fundamentalDelete'];

        return [
            'confirm training, success' => [
                [
                    'error' => null,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'confirmTraining' => $this->data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'training confirmation for player not part of training, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'trainingId' => 9999,
                        'playerId' => 9999,
                        'status' => [
                            'type' => 'ENUM',
                            'value' => 'CONFIRMED',
                        ],
                    ],
                ],
                'type_message_error' => 'playerId',
                'expected_message' => 'CheckPlayerIsInTraining.message_error',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalDelete,
                ],
                'hasPermission' => true,
            ],
            'playerId must be a required field, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'trainingId' => 9999,
                        'status' => [
                            'type' => 'ENUM',
                            'value' => 'CONFIRMED',
                        ],
                    ],
                ],
                'type_message_error' => 'playerId',
                'expected_message' => 'CheckPlayerIsInTraining.playerId_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalDelete,
                ],
                'hasPermission' => true,
            ],
            'trainingId must be a required field, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'playerId' => 9999,
                        'status' => [
                            'type' => 'ENUM',
                            'value' => 'CONFIRMED',
                        ],
                    ],
                ],
                'type_message_error' => 'trainingId',
                'expected_message' => 'CheckPlayerIsInTraining.trainingId_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalDelete,
                ],
                'hasPermission' => true,
            ],
            'status must be a required field, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'playerId' => 9999,
                        'trainingId' => 9999,
                    ],
                ],
                'type_message_error' => 'status',
                'expected_message' => 'CheckPlayerIsInTraining.status_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $fundamentalDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
