<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
use App\Models\Training;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ConfirmationTrainingTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $role = 'technician';

    public static $data = [
        'id',
        'userId',
        'playerId',
        'trainingId',
        'status',
        'presence',
        'teamId',
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
        Training::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'view-confirmation-training');
    }

    /**
     * Listagem de todos os fundamentos.
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @dataProvider listProvider
     *
     * @return void
     */
    public function confirmationsTrainingsList(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $team = Team::factory()
            ->hasPlayers(10)
            ->create();

        $training = Training::factory()
            ->setTeamId($team->id)
            ->create();

        $response = $this->graphQL(
            'confirmationsTraining',
            [
                'trainingId' => $training->id,
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
                        'confirmationsTraining' => [
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
     * Método de confirmação de Treino.
     *
     * @author Maicon Cerutti
     *
     * @dataProvider confirmPresenceProvider
     *
     * @test
     *
     * @return void
     */
    public function confirmPresence(
        $data,
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission,
        bool $trainingCancelled
    ) {
        $team = Team::factory()
            ->hasPlayers(10)
            ->create();

        $training = Training::factory()
            ->setTeamId($team->id)
            ->setStatus(!$trainingCancelled)
            ->create();

        $confirmationTraining = $training->confirmationsTraining->first();

        if ($data['error'] === null) {
            $parameters = [
                'id' => $confirmationTraining->id,
                'trainingId' => $confirmationTraining->training_id,
                'playerId' => $confirmationTraining->player_id,
                'presence' => true,
            ];
        } else {
            $parameters = $data['data_error'];
            if (
                isset($data['data_error']['trainingId']) &&
                $data['data_error']['trainingId'] == 'find'
            ) {
                $parameters['trainingId'] = $training->id;
            }
        }

        $response = $this->graphQL(
            'confirmPresence',
            $parameters,
            self::$data,
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
    public static function confirmPresenceProvider()
    {
        $confirmationTraining = ['confirmTraining'];

        return [
            'confirm presence, success' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'confirmPresence' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'training confirmation for player not part of training, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'trainingId' => 'find',
                        'playerId' => 9999,
                        'presence' => true,
                    ],
                ],
                'typeMessageError' => 'playerId',
                'expectedMessage' => 'CheckPlayerIsInTraining.message_error',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'playerId must be a required field, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'trainingId' => 'find',
                        'presence' => true,
                    ],
                ],
                'typeMessageError' => 'playerId',
                'expectedMessage' => 'CheckPlayerIsInTraining.playerId_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'trainingId must be a required field, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'playerId' => 9999,
                        'presence' => true,
                    ],
                ],
                'typeMessageError' => 'trainingId',
                'expectedMessage' => 'CheckPlayerIsInTraining.trainingId_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'presence must be a required field, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'playerId' => 9999,
                        'trainingId' => 'find',
                    ],
                ],
                'typeMessageError' => 'presence',
                'expectedMessage' => 'ConfirmTraining.presence_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'action validation with training canceled, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'trainingId' => 'find',
                    ],
                ],
                'typeMessageError' => 'trainingId',
                'expectedMessage' => 'CheckTrainingCancelled.message_error',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => true,
            ],
        ];
    }

    /**
     * Método de confirmação de Treino.
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
        bool $hasPermission,
        bool $trainingCancelled
    ) {
        $team = Team::factory()
            ->hasPlayers(10)
            ->create();

        $training = Training::factory()
            ->setTeamId($team->id)
            ->setStatus(!$trainingCancelled)
            ->create();

        $confirmationTraining = $training->confirmationsTraining->first();

        if ($data['error'] !== true) {
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
            if (
                isset($data['data_error']['trainingId']) &&
                $data['data_error']['trainingId'] == 'find'
            ) {
                $parameters['trainingId'] = $training->id;
            }
        }

        $response = $this->graphQL(
            'confirmTraining',
            $parameters,
            self::$data,
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
    public static function confirmTrainingProvider()
    {
        $confirmationTraining = ['confirmTraining'];

        return [
            'confirm training, success' => [
                [
                    'error' => null,
                    'data_error' => null,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'confirmTraining' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'training confirmation for player nott part of training, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'playerId' => 9999,
                        'status' => [
                            'type' => 'ENUM',
                            'value' => 'CONFIRMED',
                        ],
                    ],
                ],
                'typeMessageError' => 'playerId',
                'expectedMessage' => 'CheckPlayerIsInTraining.message_error',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'playerId must be a required field, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'id' => 9999,
                        'trainingId' => 'find',
                        'status' => [
                            'type' => 'ENUM',
                            'value' => 'CONFIRMED',
                        ],
                    ],
                ],
                'typeMessageError' => 'playerId',
                'expectedMessage' => 'CheckPlayerIsInTraining.playerId_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
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
                'typeMessageError' => 'trainingId',
                'expectedMessage' => 'CheckPlayerIsInTraining.trainingId_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'status must be a required field, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'trainingId' => 'find',
                    ],
                ],
                'typeMessageError' => 'status',
                'expectedMessage' => 'CheckPlayerIsInTraining.status_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => false,
            ],
            'action validation with training canceled, expected error' => [
                [
                    'error' => true,
                    'data_error' => [
                        'trainingId' => 'find',
                    ],
                ],
                'typeMessageError' => 'trainingId',
                'expectedMessage' => 'CheckTrainingCancelled.message_error',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $confirmationTraining,
                ],
                'hasPermission' => true,
                'trainingCancelled' => true,
            ],
        ];
    }
}
