<?php

namespace Tests\Feature\GraphQL;

use Faker\Factory as Faker;
use Tests\TestCase;

class TrainingConfigTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $login = true;

    private $permission = 'Técnico';

    private $data = [
        'id',
        'userId',
        'daysNotification',
        'notificationTeamByEmail',
        'notificationTechnicianByEmail',
        'createdAt',
        'updatedAt',
    ];

    /**
     * Listagem de configurações de treino.
     *
     * @author Maicon Cerutti
     * @test
     * @return void
     */
    public function trainingConfigInfo()
    {
        $this->graphQL(
            'trainingConfig',
            [
                'id' => 1,
            ],
            $this->data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'trainingConfig' => $this->data,
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de edição de configurações.
     *
     * @dataProvider ConfigEditProvider
     *
     * @author Maicon Cerutti
     * @test
     * @return void
     */
    public function trainingConfigEdit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        $permission
    ) {
        $this->checkPermission($permission, $this->permission, 'edit-training-config');

        $parameters['id'] = 1;

        $response = $this->graphQL(
            'trainingConfigEdit',
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
    public function ConfigEditProvider()
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
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $trainingConfigEdit,
                ],
                'permission' => false,
            ],
            'edit config, success' => [
                [
                    'userId' => $userId,
                    'daysNotification' => $faker->randomNumber(2),
                    'notificationTeamByEmail' => $faker->boolean,
                    'notificationTechnicianByEmail' => $faker->boolean,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'trainingConfigEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
        ];
    }
}
