<?php

namespace Tests\Feature\GraphQL;

use App\Models\Fundamental;
use App\Models\SpecificFundamental;
use Database\Seeders\Tenants\FundamentalTableSeeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SpecificFundamentalTest extends TestCase
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

        SpecificFundamental::truncate();
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
        $this->checkPermission($hasPermission, $this->role, 'edit-specific-fundamental');
        $this->checkPermission($hasPermission, $this->role, 'view-specific-fundamental');
    }

    /**
     * Listagem de todos os fundamentos especificos.
     *
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function specific_fundamentals_list(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        SpecificFundamental::factory()->make()->save();

        $response = $this->graphQL(
            'specificFundamentals',
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
                        'specificFundamentals' => [
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
     * Listagem de um fundamento especifico.
     *
     * @param  array<string, mixed>  $expected
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('infoProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function specific_fundamental_info(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $specificFundamental = SpecificFundamental::factory()->make();
        $specificFundamental->save();

        $response = $this->graphQL(
            'specificFundamental',
            [
                'id' => $specificFundamental->id,
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
                        'specificFundamental' => self::$data,
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
    #[\PHPUnit\Framework\Attributes\DataProvider('specificFundamentalCreateProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function specific_fundamental_create(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission,
        bool $addRelationship
    ) {
        $this->setPermissions($hasPermission);

        if ($parameters['name'] === 'nameExistent') {
            $specificFundamental = SpecificFundamental::factory()->create();
            $parameters['name'] = $specificFundamental->name;
        }

        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        if ($addRelationship) {
            $parameters['fundamentalId'] = $fundamental->id;
        }

        $response = $this->graphQL(
            'specificFundamentalCreate',
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
    public static function specificFundamentalCreateProvider(): array
    {
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name;
        $specificFundamentalCreate = ['specificFundamentalCreate'];

        return [
            'create specific fundamental, with relationship, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'addRelationship' => true,
            ],
            'create specific fundamental, no relationship, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalCreate' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'addRelationship' => false,
            ],
            'create specific fundamental without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $specificFundamentalCreate,
                ],
                'hasPermission' => false,
                'addRelationship' => false,
            ],
            'name field is not unique, expected error' => [
                [
                    'name' => 'nameExistent',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'SpecificFundamentalCreate.name_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $specificFundamentalCreate,
                ],
                'hasPermission' => true,
                'addRelationship' => false,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'SpecificFundamentalCreate.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $specificFundamentalCreate,
                ],
                'hasPermission' => true,
                'addRelationship' => false,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'SpecificFundamentalCreate.name_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $specificFundamentalCreate,
                ],
                'hasPermission' => true,
                'addRelationship' => false,
            ],
        ];
    }

    /**
     * Método de edição de um fundamento especifico.
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
        bool $addRelationship
    ) {
        $this->setPermissions($hasPermission);

        $specificFundamentalExist = SpecificFundamental::factory()->make();
        $specificFundamentalExist->save();
        $specificFundamental = SpecificFundamental::factory()->make();
        $specificFundamental->save();

        $parameters['id'] = $specificFundamental->id;

        if ($expectedMessage == 'SpecificFundamentalEdit.name_unique') {
            $parameters['name'] = $specificFundamentalExist->name;
        }

        $fundamental = Fundamental::factory()->make();
        $fundamental->save();

        if ($addRelationship) {
            $parameters['fundamentalId'] = $fundamental->id;
        }

        $response = $this->graphQL(
            'specificFundamentalEdit',
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
    public static function specificFundamentalEditProvider(): array
    {
        $faker = Faker::create();
        $userId = 2;
        $fundamentalEdit = ['specificFundamentalEdit'];

        return [
            'edit specific fundamental without permission, expected error' => [
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
                'addRelationship' => false,
            ],
            'edit specific fundamental, no relationship, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'addRelationship' => false,
            ],
            'edit specific fundamental, with relationship, success' => [
                [
                    'name' => $faker->name,
                    'userId' => $userId,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
                'addRelationship' => true,
            ],
            'name field is not unique, expected error' => [
                [
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'SpecificFundamentalEdit.name_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalEdit,
                ],
                'hasPermission' => true,
                'addRelationship' => false,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'SpecificFundamentalEdit.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalEdit,
                ],
                'hasPermission' => true,
                'addRelationship' => false,
            ],
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'SpecificFundamentalEdit.name_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $fundamentalEdit,
                ],
                'hasPermission' => true,
                'addRelationship' => false,
            ],
        ];
    }

    /**
     * Método de exclusão de um time.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('specificFundamentalDeleteProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function specific_fundamental_delete(
        array $data,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $specificFundamental = SpecificFundamental::factory()->make();
        $specificFundamental->save();

        /** @var array<string, mixed> $params */
        $params = $data;

        $params['id'] = $specificFundamental->id;

        if ($data['error'] != null) {
            unset($params['error']);
            $params['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'specificFundamentalDelete',
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
    public static function specificFundamentalDeleteProvider()
    {
        $specificFundamentalDelete = ['specificFundamentalDelete'];

        return [
            'delete specific fundamental, success' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'specificFundamentalDelete' => [self::$data],
                    ],
                ],
                'hasPermission' => true,
            ],
            'delete specific fundamental without permission, expected error' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $specificFundamentalDelete,
                ],
                'hasPermission' => false,
            ],
            'delete specific fundamental that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => 'internal',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $specificFundamentalDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
