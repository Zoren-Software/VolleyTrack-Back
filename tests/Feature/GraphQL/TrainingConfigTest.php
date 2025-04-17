<?php

namespace Tests\Feature\GraphQL;

use Faker\Factory as Faker;
use Tests\TestCase;

class TrainingConfigTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $role = 'technician';

    public static $data = [
        'id',
        'userId',
        'daysNotification',
        'notificationTeamByEmail',
        'notificationTechnicianByEmail',
        'createdAt',
        'updatedAt',
    ];

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
     * @test
     *
     * @dataProvider infoProvider
     *
     * @return void
     */
    public function trainingConfigInfo(
        $typeMessageError,
        $expectedMessage,
        $expected,
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
     * @dataProvider ConfigEditProvider
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @return void
     */
    public function trainingConfigEdit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
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
     * @return array
     */
    public static function ConfigEditProvider()
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
