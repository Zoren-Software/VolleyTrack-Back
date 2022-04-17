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
     * @author Maicon Cerutti
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
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_user_info()
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
     * @dataProvider userCreateProvider
     * @author Maicon Cerutti
     * 
     * @return void
     */
    public function test_user_create($password, $email, $expected)
    {
        $faker = Faker::create();

        $response = $this->graphQL(/** @lang GraphQL */ '
            userCreate (
                name: "' . $faker->name . '"
                email: "' . $email . '"
                password: "' . $password . '"
            ) {
                id
                name
                email
                email_verified_at
                created_at
                updated_at
            }
        ', 'mutation');
        
        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * 
     * @return Array
     */
    public function userCreateProvider()
    {
        $faker = Faker::create();
        $emailExistent = $faker->email;

        return [
            'text password less than 6 characters' => [
                'password' => '12345',
                'email' => $faker->email,
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'extensions',
                            'path',
                            'trace'
                        ]
                    ],
                    'data' => [
                        'userCreate'
                    ]
                ],
            ],
            'no text password' => [
                'password' => '',
                'email' => $faker->email,
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'extensions',
                            'path',
                            'trace'
                        ]
                    ],
                    'data' => [
                        'userCreate'
                    ]
                ],
            ],
            'create user' => [
                'password' => '123456',
                'email' => $emailExistent,
                'expected' => [
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
                ],
            ],
            'text password with 6 characters' => [
                'password' => '123456',
                'email' => $faker->email,
                'expected' => [
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
                ],
            ],
            'email field is required' => [
                'password' => '123456',
                'email' => '',
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'extensions',
                            'path',
                            'trace'
                        ]
                    ],
                    'data' => [
                        'userCreate'
                    ]
                ],
            ],
            'email field is required' => [
                'password' => '123456',
                'email' => '',
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'extensions',
                            'path',
                            'trace'
                        ]
                    ],
                    'data' => [
                        'userCreate'
                    ]
                ],
            ],
            'email field is not unique' => [
                'password' => '123456',
                'email' => $emailExistent,
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'extensions',
                            'path',
                            'trace'
                        ]
                    ],
                    'data' => [
                        'userCreate'
                    ]
                ],
            ],
            'email field is not email valid' => [
                'password' => '123456',
                'email' => 'naosouemail.com',
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'extensions',
                            'path',
                            'trace'
                        ]
                    ],
                    'data' => [
                        'userCreate'
                    ]
                ],
            ],
        ];
    }
}
