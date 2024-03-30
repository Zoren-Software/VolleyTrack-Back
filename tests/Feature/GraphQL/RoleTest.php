<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;

class RoleTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

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
     * @test
     *
     * @return void
     */
    public function roleInfo()
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
     * @test
     *
     * @return void
     */
    public function rolesList()
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
