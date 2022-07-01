<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;

class RoleTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    /**
     * Listagem de uma role
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_role_info()
    {
        $this->login = true;

        $data = [
            'id',
            'name',
            'createdAt',
            'updatedAt'
        ];

        $response = $this->graphQL(
            'role',
            [
                'id' => 2,
            ],
            $data,
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'role' => $data,
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
    public function test_roles_list()
    {
        $this->login = true;

        $paginatorInfo = [
            'count',
            'currentPage',
            'firstItem',
            'lastItem',
            'lastPage',
            'perPage',
            'total',
            'hasMorePages'
        ];

        $data = [
            'id',
            'name',
            'createdAt',
            'updatedAt',

        ];

        $response = $this->graphQL(
            'roles',
            [
                'name' => '%%',
                'first' => 10,
                'page' => 1,
            ],
            [
                'paginatorInfo' => $paginatorInfo,
                'data' => $data,
            ],
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'roles' => [
                    'paginatorInfo' => $paginatorInfo,
                    'data' => [
                        '*' => $data
                    ]
                ],
            ],
        ])->assertStatus(200);
    }
}
