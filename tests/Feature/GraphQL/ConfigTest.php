<?php

namespace Tests\Feature\GraphQL;

use Faker\Factory as Faker;
use Tests\TestCase;

class ConfigTest extends TestCase
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
        'nameTenant',
        'languageId',
        'createdAt',
        'updatedAt',
    ];

    /**
     * @return void
     */
    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-config');
        $this->checkPermission($hasPermission, $this->role, 'view-config');
    }

    /**
     * Listagem de configurações.
     *
     * @author Maicon Cerutti
     *
     * @param  array<int, string>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('infoProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function config_info(
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $response = $this->graphQL(
            'config',
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
                        'config' => self::$data,
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
     * @param  array<int, string>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('configEditProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function config_edit(
        array $parameters,
        bool|string $typeMessageError,
        bool|string $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $response = $this->graphQL(
            'configEdit',
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
    public static function configEditProvider(): array
    {
        $faker = Faker::create();
        $userId = 2;
        $configEdit = ['configEdit'];

        return [
            'edit config without permission, expected error' => [
                [
                    'nameTenant' => 'Test',
                    'languageId' => 1,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $configEdit,
                ],
                'hasPermission' => false,
            ],
            'edit config, success' => [
                [
                    'nameTenant' => 'Test',
                    'languageId' => 1,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'configEdit' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'nameTenant field is required, expected error' => [
                [
                    'nameTenant' => ' ',
                ],
                'typeMessageError' => 'nameTenant',
                'expectedMessage' => 'ConfigEdit.name_tenant_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $configEdit,
                ],
                'hasPermission' => true,
            ],
            'nameTenant field is min 3 characteres, expected error' => [
                [
                    'nameTenant' => 'AB',
                ],
                'typeMessageError' => 'nameTenant',
                'expectedMessage' => 'ConfigEdit.name_tenant_min',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $configEdit,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
