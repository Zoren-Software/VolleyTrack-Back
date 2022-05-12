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

        $response = $this->graphQL(
            'users', 
            [
                'email' => $user->email,
                'password' => 'password',
            ], 
            ['token'], 
            'query', 
            false
        );
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
        ');
        //dd($response);
        $response->assertJsonStructure([
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
    public function test_user_create($password, $email, $type_message_error, $expected_message, $expected)
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

        if ($type_message_error) {
            $this->assertSame($response->json()['errors'][0]['extensions']['validation'][$type_message_error][0], trans($expected_message));
        }

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
            'text password less than 6 characters, expected error' => [
                'password' => '12345',
                'email' => $faker->email,
                'type_message_error' => 'password',
                'expected_message' => 'UserCreate.password_min_6',
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
            'no text password, expected error' => [
                'password' => '',
                'email' => $faker->email,
                'type_message_error' => 'password',
                'expected_message' => 'UserCreate.password_required',
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
            'create user, success' => [
                'password' => '123456',
                'email' => $emailExistent,
                'type_message_error' => false,
                'expected_message' => false,
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
            'text password with 6 characters, success' => [
                'password' => '123456',
                'email' => $faker->email,
                'type_message_error' => false,
                'expected_message' => false,
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
            'email field is required, expected error' => [
                'password' => '123456',
                'email' => '',
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_required',
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
            'email field is not unique, expected error' => [
                'password' => '123456',
                'email' => $emailExistent,
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_unique',
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
            'email field is not email valid, expected error' => [
                'password' => '123456',
                'email' => 'notemail.com',
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_is_valid',
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
