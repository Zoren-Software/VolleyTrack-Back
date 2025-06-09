<?php

namespace Tests\Feature\GraphQL;

use App\Models\Position;
use Database\Seeders\Tenants\PositionTableSeeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PositionTest extends TestCase
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
        'name',
        'userId',
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

        Position::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seed([
            PositionTableSeeder::class,
        ]);
    }

    /**
     * @return void
     */
    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-position');
        $this->checkPermission($hasPermission, $this->role, 'view-position');
    }

    /**
     * Listagem de todos os fundamentos.
     *
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function positions_list(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        Position::factory()->make()->save();

        $response = $this->graphQL(
            'positions',
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
                        'positions' => [
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
     * Listagem de um fundamento
     *
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('infoProvider')]
    public function position_info(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $position = Position::factory()->make();
        $position->save();

        $response = $this->graphQL(
            'position',
            [
                'id' => $position->id,
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
            $response
                ->assertJsonStructure($expected)
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
                        'position' => self::$data,
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
     * Método de criação de um fundamento.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('positionCreateProvider')]
    public function position_create(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->checkPermission($hasPermission, $this->role, 'edit-position');

        if ($parameters['name'] === 'nameExistent') {
            $position = Position::factory()->create();
            $parameters['name'] = $position->name;
        }

        $response = $this->graphQL(
            'positionCreate',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function positionCreateProvider(): array
    {
        $faker = Faker::create();
        $userId = 1;
        $positionCreate = ['positionCreate'];

        return [
            'create position, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'positionCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create position without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionCreate,
                ],
                'hasPermission' => false,
            ],
            'name field is not unique, expected error' => [
                [
                    'name' => 'nameExistent',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'PositionCreate.name_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'PositionCreate.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'PositionCreate.name_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionCreate,
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um fundamento.
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('positionEditProvider')]
    public function position_edit(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->checkPermission($hasPermission, $this->role, 'edit-position');

        $positionExist = Position::factory()->make();
        $positionExist->save();
        $position = Position::factory()->make();
        $position->save();

        $parameters['id'] = $position->id;

        if ($expectedMessage == 'PositionEdit.name_unique') {
            $parameters['name'] = $positionExist->name;
        }

        $response = $this->graphQL(
            'positionEdit',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function positionEditProvider(): array
    {
        $faker = Faker::create();
        $userId = 2;
        $positionEdit = ['positionEdit'];

        return [
            'edit position without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionEdit,
                ],
                'hasPermission' => false,
            ],
            'edit position, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'positionEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'PositionEdit.name_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionEdit,
                ],
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'PositionEdit.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionEdit,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'PositionEdit.name_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionEdit,
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * Método de exclusão de uma posição.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('positionDeleteProvider')]
    public function position_delete(
        array $data,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->login = true;

        $this->checkPermission($hasPermission, $this->role, 'edit-position');

        $position = Position::factory()->make();
        $position->save();

        /** @var array<string, mixed> $params */
        $params = $data;

        if (array_key_exists('error', $data) && $data['error'] !== null) {
            $params['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'positionDelete',
            $params,
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
     * @author Maicon Cerutti
     *
     * @return array<string, array<int|string, mixed>>
     */
    public static function positionDeleteProvider(): array
    {
        $positionDelete = ['positionDelete'];

        return [
            'delete position, success' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'positionDelete' => [self::$data],
                    ],
                ],
                'hasPermission' => true,
            ],
            'delete position without permission, expected error' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionDelete,
                ],
                'hasPermission' => false,
            ],
            'delete position that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => 'internal',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $positionDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
