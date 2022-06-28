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

        $saida = [
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
            $saida,
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'role' => $saida,
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

        $response = $this->graphQL(
            'roles',
            [
                'name' => '%%',
                'first' => 10,
                'page' => 1,
            ],
            [
                'paginatorInfo' => [
                    'count',
                    'currentPage',
                    'firstItem',
                    'lastItem',
                    'lastPage',
                    'perPage',
                    'total',
                    'hasMorePages'
                ],
                'data' => [
                    'id',
                    'name',
                    'createdAt',
                    'updatedAt',

                ],
            ],
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'roles' => [
                    'paginatorInfo' => [
                        'count',
                        'currentPage',
                        'firstItem',
                        'lastItem',
                        'lastPage',
                        'perPage',
                        'total',
                        'hasMorePages',
                    ],
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ],
            ],
        ])->assertStatus(200);
    }
}