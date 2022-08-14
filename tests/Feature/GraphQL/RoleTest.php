<?php

namespace Tests\Feature\GraphQL;

use Tests\TestCase;

class RoleTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    private $data = [
        'id',
        'name',
        'createdAt',
        'updatedAt'
    ];

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

        $this->graphQL(
            'role',
            [
                'id' => 2,
            ],
            $this->data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'role' => $this->data,
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

        $this->graphQL(
            'roles',
            [
                'name' => '%%',
                'first' => 10,
                'page' => 1,
            ],
            [
                'paginatorInfo' => $this->paginatorInfo,
                'data' => $this->data,
            ],
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'roles' => [
                    'paginatorInfo' => $this->paginatorInfo,
                    'data' => [
                        '*' => $this->data
                    ]
                ],
            ],
        ])->assertStatus(200);
    }
}
