<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;

class TeamLevelTest extends TestCase
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
        'name',
        'description',
        'createdAt',
        'updatedAt',
    ];

    /**
     * @return void
     */
    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'view-team-levels');
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
    public function team_levels_list(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $response = $this->graphQL(
            'teamLevels',
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
                        'teamLevels' => [
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
    public function team_level_info(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $response = $this->graphQL(
            'teamLevel',
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
                        'teamLevel' => self::$data,
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
}
