<?php

namespace Tests\Feature\GraphQL;

use Faker\Factory as Faker;
use Tests\TestCase;

class TrainingConfigTest extends TestCase
{
    /**
     * @var bool
     */
    protected $graphql = true;

    /**
     * @var bool
     */
    protected $tenancy = true;

    /**
     * @var bool
     */
    protected $login = true;

    /**
     * @var string
     */
    private $role = 'technician';

    /**
     * @var array<int, string>
     */
    public static $data = [
        'id',
        'userId',
        'daysNotification',
        'notificationTeamByEmail',
        'notificationTechnicianByEmail',
        'createdAt',
        'updatedAt',
    ];

    /**
     * @return void
     */
    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-training-config');
        $this->checkPermission($hasPermission, $this->role, 'view-training-config');
    }

    /**
     * Listagem de configurações de treino.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('infoProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function training_config_info(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $response = $this->graphQL(
            'trainingConfig',
            [
                'id' => 1,
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
                        'trainingConfig' => self::$data,
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
     * Método de edição de configurações.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('ConfigEditProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function training_config_edit(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $parameters['id'] = 1;

        $response = $this->graphQL(
            'trainingConfigEdit',
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
    public static function ConfigEditProvider(): array
    {
        $faker = Faker::create();
        $userId = 2;
        $trainingConfigEdit = ['trainingConfigEdit'];

        return [
            'edit config without permission, expected error' => [
                [
                    'userId' => $userId,
                    'daysNotification' => $faker->randomNumber(2),
                    'notificationTeamByEmail' => $faker->boolean,
                    'notificationTechnicianByEmail' => $faker->boolean,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingConfigEdit,
                ],
                'hasPermission' => false,
            ],
            'edit config with permission, not required userId , expected error' => [
                [
                    'daysNotification' => $faker->randomNumber(2),
                    'notificationTeamByEmail' => $faker->boolean,
                    'notificationTechnicianByEmail' => $faker->boolean,
                ],
                'typeMessageError' => 'userId',
                'expectedMessage' => 'TrainingConfigEdit.user_id_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $trainingConfigEdit,
                ],
                'hasPermission' => true,
            ],
            'edit config, success' => [
                [
                    'userId' => $userId,
                    'daysNotification' => $faker->randomNumber(2),
                    'notificationTeamByEmail' => $faker->boolean,
                    'notificationTechnicianByEmail' => $faker->boolean,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'trainingConfigEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
