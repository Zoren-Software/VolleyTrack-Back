<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;

class RoleTest extends TestCase
{
    protected bool $graphql = true;

    protected bool $tenancy = true;

    /**
     * @var array<int, string>
     */
    public static $data = [
        'id',
        'name',
        'createdAt',
        'updatedAt',
    ];

    /**
     * Listagem de uma role
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function role_info()
    {
        $this->login = true;

        $this->graphQL(
            'role',
            [
                'id' => 2,
            ],
            self::$data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'role' => self::$data,
            ],
        ])->assertStatus(200);
    }

    /**
     * Listagem de todos as roles.
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function roles_list()
    {
        $this->login = true;

        $this->graphQL(
            'roles',
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
        )->assertJsonStructure([
            'data' => [
                'roles' => [
                    'paginatorInfo' => self::$paginatorInfo,
                    'data' => [
                        '*' => self::$data,
                    ],
                ],
            ],
        ])->assertStatus(200);
    }
}
