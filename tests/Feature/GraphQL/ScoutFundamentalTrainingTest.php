<?php

namespace Tests\Feature\GraphQL;

use App\Models\ScoutFundamentalTraining;
use App\Models\Training;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ScoutFundamentalTrainingTest extends TestCase
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
        'positionId',
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

        ScoutFundamentalTraining::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * @return void
     */
    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-team');
        $this->checkPermission($hasPermission, $this->role, 'view-team');
    }

    /**
     * Listagem de todos os times.
     *
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function scout_fundamental_trainings_list(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        ScoutFundamentalTraining::factory()->create();

        $response = $this->graphQL(
            'scoutFundamentalTrainings',
            [
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
    public static function listProvider(): array
    {
        return [
            'with permission' => [
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'scoutFundamentalTrainings' => [
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
     * Listagem de um time
     *
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('infoProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function scout_fundamental_training_info(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $scoutFundamentalTraining = ScoutFundamentalTraining::factory()->create();

        $response = $this->graphQL(
            'scoutFundamentalTraining',
            [
                'id' => $scoutFundamentalTraining->id,
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function infoProvider(): array
    {
        return [
            'with permission' => [
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'scoutFundamentalTraining' => self::$data,
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
     * Método de criação de um fundamento especifico.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('specificFundamentalEditProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function specific_fundamental_edit(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission,
    ) {
        $this->setPermissions($hasPermission);

        $training = Training::factory()->make();
        $training->save();

        $parameters['trainingId'] = $training->id;

        $player = $training->team->players()->get()->random();

        $parameters['playerId'] = $player->id;
        $parameters['positionId'] = $player->positions()->get()->random()->id;

        // TODO - Buscar o scout fundamental training pelo id do treino já criado

        $response = $this->graphQL(
            'scoutFundamentalTrainingEdit',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        dd($response->json());

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function specificFundamentalEditProvider(): array
    {
        $userId = 1;
        $scoutFundamentalTrainingEdit = ['scoutFundamentalTrainingEdit'];
        $faker = Faker::create();

        return [
            'create scout fundamental training, with relationship, success' => [
                [

                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'scoutFundamentalTrainingCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],

        ];
    }
}
