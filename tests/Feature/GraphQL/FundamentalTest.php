<?php

namespace Tests\Feature\GraphQL;

use App\Models\Fundamental;
use Database\Seeders\Tenants\FundamentalTableSeeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FundamentalTest extends TestCase
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
        Fundamental::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seed([
            FundamentalTableSeeder::class,
        ]);
    }

    /**
     * @return void
     */
    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-fundamental');
        $this->checkPermission($hasPermission, $this->role, 'view-fundamental');
    }

    /**
     * Listagem de todos os fundamentos.
     *
     * @author Maicon Cerutti
     *
     * @param  array<int, string>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    public function fundamentals_list(
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        Fundamental::factory()->make()->save();

        $response = $this->graphQL(
            'fundamentals',
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
     * @return array<string, array<string, mixed>>
     */
    public static function listProvider()
    {
        return [
            'with permission' => [
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'fundamentals' => [
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
     * @author Maicon Cerutti
     *
     * @param  array<int, string>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('infoProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function fundamental_info(
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        $response = $this->graphQL(
            'fundamental',
            [
                'id' => $fundamental->id,
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
    public static function infoProvider()
    {
        return [
            'with permission' => [
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'fundamental' => self::$data,
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
     * @param  array<int, string>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('fundamentalCreateProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function fundamental_create(
        array $parameters,
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        if ($expectedMessage == 'FundamentalCreate.name_unique') {
            $fundamental = Fundamental::factory()->make();
            $fundamental->save();
            $parameters['name'] = $fundamental->name;
        }

        $response = $this->graphQL(
            'fundamentalCreate',
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
    public static function fundamentalCreateProvider(): array
    {
        $faker = Faker::create();
        $userId = 1;
        $fundamentalCreate = ['fundamentalCreate'];

        return [
            'create fundamental, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'fundamentalCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'create fundamental without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalCreate,
                ],
                'hasPermission' => false,
            ],
            'name field is not unique, expected error' => [
                [
                    'name' => 'FundamentalCreate.name_unique',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'FundamentalCreate.name_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'FundamentalCreate.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalCreate,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'FundamentalCreate.name_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalCreate,
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um fundamento.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<int, string>  $expected
     * @param  bool  $hasPermission
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('fundamentalEditProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function fundamental_edit(
        array $parameters,
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
        $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $fundamentalExist = Fundamental::factory()->make();
        $fundamentalExist->save();
        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        /** @var array<string, mixed> $params */
        $params = $parameters;

        $params['id'] = $fundamental->id;

        if ($expectedMessage == 'FundamentalEdit.name_unique') {
            $params['name'] = $fundamentalExist->name;
        }

        $response = $this->graphQL(
            'fundamentalEdit',
            $params,
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
    public static function fundamentalEditProvider(): array
    {
        $faker = Faker::create();
        $userId = 2;
        $fundamentalEdit = ['fundamentalEdit'];

        return [
            'edit fundamental without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalEdit,
                ],
                'hasPermission' => false,
            ],
            'edit fundamental, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'fundamentalEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'FundamentalEdit.name_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalEdit,
                ],
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'FundamentalEdit.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalEdit,
                ],
                'hasPermission' => true,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'FundamentalEdit.name_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalEdit,
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * Método de exclusão de um fundamento.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $expected
     * @param  bool  $hasPermission
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('fundamentalDeleteProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function fundamental_delete(
        array $data,
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
        $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        /** @var array<string, mixed> $params */
        $params = $data;

        $params['id'] = $fundamental->id;

        if ($data['error'] != null) {
            unset($params['error']);
            $params['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'fundamentalDelete',
            $params,
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
     * @author Maicon Cerutti
     *
     * @return array<string, array<int|string, mixed>>
     */
    public static function fundamentalDeleteProvider(): array
    {
        $fundamentalDelete = ['fundamentalDelete'];

        return [
            'delete fundamental, success' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'fundamentalDelete' => [self::$data],
                    ],
                ],
                'hasPermission' => true,
            ],
            'delete fundamental without permission, expected error' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalDelete,
                ],
                'hasPermission' => false,
            ],
            'delete fundamental that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => 'internal',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
