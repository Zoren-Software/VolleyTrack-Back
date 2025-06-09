<?php

namespace Tests\Feature\GraphQL;

use App\Models\ConfirmationTraining;
use App\Models\Team;
use App\Models\Training;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ConfirmationTrainingTest extends TestCase
{
    protected bool $graphql = true;

    protected bool $tenancy = true;

    protected bool $login = true;

    private string $role = 'technician';

    /**
     * @var array<int, string>
     */
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
        ConfirmationTraining::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * @return void
     */
    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'view-confirmation-training');
    }

    /**
     * Listagem de todos os fundamentos.
     *
     * @author Maicon Cerutti
     *
     * @param  array<int, string>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function confirmations_trainings_list(
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
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
     * @return array<string, array<int|string, mixed>>
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
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('confirmPresenceProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function confirm_presence(
        array $data,
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
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

        $confirmationTraining = $training->confirmationsTraining->firstOrFail();

        if ($data['error'] === null) {
            $parameters = [
                'id' => $confirmationTraining->id,
                'trainingId' => $confirmationTraining->training_id,
                'playerId' => $confirmationTraining->player_id,
                'presence' => true,
            ];
        } else {
            /** @var array<string, mixed> $parameters */
            $parameters = $data['data_error']; // agora garantido como array

            if (
                isset($parameters['trainingId']) &&
                $parameters['trainingId'] === 'find'
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function confirmPresenceProvider(): array
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
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('confirmTrainingProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function confirm_training(
        array $data,
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
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

        $confirmationTraining = $training->confirmationsTraining->firstOrFail();

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
            /** @var array<string, mixed> $parameters */
            $parameters = $data['data_error']; // agora garantido como array

            if (
                isset($parameters['trainingId']) &&
                $parameters['trainingId'] === 'find'
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function confirmTrainingProvider(): array
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
