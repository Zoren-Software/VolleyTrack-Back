<?php

namespace Tests\Feature\GraphQL;

use App\Models\User;
use Faker\Factory as Faker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $otherUser = false;

    /**
     * Listagem de todos os usuários.
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_users_list()
    {
        $this->login = true;

        User::factory()->make()->save();

        $response = $this->graphQL(
            'users',
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
                    'email',
                    'createdAt',
                    'updatedAt',

                ],
            ],
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'users' => [
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
                            'email',
                            'createdAt',
                            'updatedAt'
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
        $this->login = true;

        $user = User::factory()->make();
        $user->save();

        $saida = [
            'id',
            'name',
            'email',
            'emailVerifiedAt',
            'createdAt',
            'updatedAt'
        ];

        $response = $this->graphQL(
            'user',
            [
                'id' => $user->id,
            ],
            $saida,
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'user' => $saida,
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de criação de um usuário.
     *
     * @dataProvider userCreateProvider
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_user_create($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->login = true;

        $faker = Faker::create();

        $this->checkPermission($permission, 'Técnico', 'create-user');

        $parameters['name'] = $faker->name;

        $response = $this->graphQL(
            'userCreate',
            $parameters,
            [
                'id',
                'name',
                'email',
                'emailVerifiedAt',
                'createdAt',
                'updatedAt'
            ],
            'mutation',
            false,
            true
        );

        $this->assertMessageError($type_message_error, $response, $permission, $expected_message);

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
            'declare roleId is required, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => '12345',
                    'roleId' => [ ],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'UserCreate.role_id_required',
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
                'permission' => true,
            ],
            'create user, success' => [
                [
                    'name' => $faker->name,
                    'email' => $emailExistent,
                    'roleId' => [ 2 ],
                    'password' => '123456',
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => [
                            'id',
                            'name',
                            'email',
                            'emailVerifiedAt',
                            'createdAt',
                            'updatedAt'
                        ],
                    ],
                ],
                'permission' => true,
            ],
            'create user with permission that shouldnt have, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [ 1, 2 ],
                    'password' => '123456',
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'PermissionAssignment.validation_message_error',
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
                'permission' => true,
            ],
            'create user without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [ 2 ],
                    'password' => '123456',
                ],
                'type_message_error' => 'message',
                'expected_message' => 'This action is unauthorized.',
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
                'permission' => false,
            ],
            'text password less than 6 characters, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [ 2 ],
                    'password' => '12345',
                ],
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
                'permission' => true,
            ],
            'no text password, expected error' => [
                [
                    'password' => ' ',
                    'email' => $faker->email,
                    'roleId' => [ 2 ],
                ],
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
                'permission' => true,
            ],
            'text password with 6 characters, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [ 2 ],
                    'password' => '123456',
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => [
                            'id',
                            'name',
                            'email',
                            'emailVerifiedAt',
                            'createdAt',
                            'updatedAt'
                        ],
                    ],
                ],
                'permission' => true,
            ],
            'email field is required, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => '123456',
                    'roleId' => [ 2 ],
                    'email' => ' ',
                ],
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
                'permission' => true,
            ],
            'email field is not unique, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => '123456',
                    'roleId' => [ 2 ],
                    'email' => $emailExistent,
                ],
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
                'permission' => true,
            ],
            'email field is not email valid, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => '123456',
                    'roleId' => [ 2 ],
                    'email' => 'notemail.com',
                ],
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
                'permission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um usuário.
     *
     * @dataProvider userEditProvider
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_user_edit($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->login = true;

        $this->checkPermission($permission, 'Técnico', 'edit-user');

        $userExist = User::factory()->make();
        $userExist->save();
        $user = User::factory()->make();
        $user->save();

        $parameters['id'] = $user->id;

        if ($expected_message == 'UserEdit.email_unique') {
            $parameters['email'] = $userExist->email;
        }

        $response = $this->graphQL(
            'userEdit',
            $parameters,
            [
                'id',
                'name',
                'email',
                'emailVerifiedAt',
                'createdAt',
                'updatedAt'
            ],
            'mutation',
            false,
            true
        );

        $this->assertMessageError($type_message_error, $response, $permission, $expected_message);

        if ($type_message_error) {
            if (!$permission) {
                $this->assertSame($response->json()['errors'][0][$type_message_error], $expected_message);
            } else {
                $this->assertSame($response->json()['errors'][0]['extensions']['validation'][$type_message_error][0], trans($expected_message));
            }
        }

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     *
     * @return Array
     */
    public function userEditProvider()
    {
        $faker = Faker::create();

        $password = '123456';

        return [
            'declare roleId is required, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [ ],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'UserEdit.role_id_required',
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
                        'userEdit'
                    ]
                ],
                'permission' => true,
            ],
            'edit user with permission that shouldnt have, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [ 1, 2 ],
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserEdit.password_min_6',
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
                        'userEdit'
                    ]
                ],
                'permission' => true,
            ],
            'edit user without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [ 2 ],
                ],
                'type_message_error' => 'message',
                'expected_message' => 'This action is unauthorized.',
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
                        'userEdit'
                    ]
                ],
                'permission' => false,
            ],
            'edit user, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [ 2 ],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => [
                            'id',
                            'name',
                            'email',
                            'emailVerifiedAt',
                            'createdAt',
                            'updatedAt'
                        ],
                    ],
                ],
                'permission' => true,
            ],
            'text password less than 6 characters, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => '12345',
                    'roleId' => [ 2 ],
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserEdit.password_min_6',
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
                        'userEdit'
                    ]
                ],
                'permission' => true,
            ],
            'no text password, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => ' ',
                    'email' => $faker->email,
                    'roleId' => [ 2 ],
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserEdit.password_required',
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
                        'userEdit'
                    ]
                ],
                'permission' => true,
            ],
            'text password with 6 characters, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [ 2 ],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => [
                            'id',
                            'name',
                            'email',
                            'emailVerifiedAt',
                            'createdAt',
                            'updatedAt'
                        ],
                    ],
                ],
                'permission' => true,
            ],
            'email field is required, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'email' => ' ',
                    'roleId' => [ 2 ],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_required',
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
                        'userEdit'
                    ]
                ],
                'permission' => true,
            ],
            'email field is not unique, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'roleId' => [ 2 ],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_unique',
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
                        'userEdit'
                    ]
                ],
                'permission' => true,
            ],
            'email field is not email valid, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'email' => 'notemail.com',
                    'roleId' => [ 2 ],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_is_valid',
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
                        'userEdit'
                    ]
                ],
                'permission' => true,
            ],
        ];
    }
}
