<?php

namespace Tests\Feature\GraphQL;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    // use RefreshDatabase;

    protected $graphql = true;

    protected $tenancy = true;

    /**
     * Listagem de todos os usuários.
     *
     * @return void
     */
    public function test_users_list()
    {
        User::factory()->make()->save();
        $response = $this->graphQL(/** @lang GraphQL */ '
            users {
                paginatorInfo {
                    count
                    currentPage
                    firstItem
                    hasMorePages
                    lastItem
                    lastPage
                    perPage
                    total
                }
                data {
                    id
                    name
                    email
                    email_verified_at
                    created_at
                    updated_at
                }
            }
        ')->assertJsonStructure([
            'data' => [
                'users' => [
                    'paginatorInfo' => [
                        'count',
                        'currentPage',
                        'firstItem',
                        'hasMorePages',
                        'lastItem',
                        'lastPage',
                        'perPage',
                        'total'
                    ],
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'email_verified_at',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Listagem de um usuário
     *
     * @return void
     */
    public function test_user_list()
    {
        //$this->withoutExceptionHandling();
        $user = User::factory()->make();
        $user->save();

        $response = $this->graphQL(/** @lang GraphQL */ "
            user (id: $user->id) {
                id
                name
                email
                email_verified_at
                created_at
                updated_at
            }
        ");

        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Listagem de um usuário
     *
     * @return void
     */
    public function test_user_create()
    {
        $faker = Faker::create();

        $response = $this->graphQL(/** @lang GraphQL */ '
            userCreate (
                name: "' . $faker->name . '"
                email: "' . $faker->email . '"
                password: "password"
            ) {
                id
                name
                email
                email_verified_at
                created_at
                updated_at
            }
        ', 'mutation');

        $response->assertJsonStructure([
            'data' => [
                'userCreate' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                ],
            ],
        ])->assertStatus(200);
    }
}
