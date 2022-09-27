<?php

namespace Tests\Feature\GraphQL;

use App\Models\User;
use App\Models\Position;
use Faker\Factory as Faker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $otherUser = false;

    private $permission = 'Técnico';

    private $data = [
        'id',
        'name',
        'email',
        'emailVerifiedAt',
        'createdAt',
        'updatedAt',
    ];

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

        User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        $this->graphQL(
            'users',
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
                'users' => [
                    'paginatorInfo' => $this->paginatorInfo,
                    'data' => [
                        '*' => $this->data,
                    ],
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

        $user = User::factory()
            ->has(Position::factory()->count(3))
            ->create();
        $user->save();

        $this->graphQL(
            'user',
            [
                'id' => $user->id,
            ],
            $this->data,
            'query',
            false
        )->assertJsonStructure([
            'data' => [
                'user' => $this->data,
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de criação de um usuário.
     *
     * @dataProvider userCreateProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_user_create($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->login = true;

        $faker = Faker::create();

        $this->checkPermission($permission, $this->permission, 'create-user');

        $parameters['name'] = $faker->name;

        $response = $this->graphQL(
            'userCreate',
            $parameters,
            $this->data,
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
     * @return array
     */
    public function userCreateProvider()
    {
        $faker = Faker::create();
        $emailExistent = $faker->email;

        $password = env('PASSWORD_TEST', '123456');

        $userCreate = ['userCreate'];

        return [
            'create user with teams, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [2],
                    'positionId' => [1],
                    'teamId' => [1, 2],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create user with position, success' => [
                [
                    'name' => $faker->name,
                    'email' => $emailExistent,
                    'roleId' => [2],
                    'positionId' => [1],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'declare roleId is required, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'UserCreate.role_id_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'permission' => true,
            ],
            'create user, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [3],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create user with 2 roles, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [2, 3],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'create user with permission that shouldnt have, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [1, 2],
                    'password' => $password,
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'PermissionAssignment.validation_message_error',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'permission' => true,
            ],
            'create user without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [2],
                    'password' => $password,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'permission' => false,
            ],
            'text password less than 6 characters, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [2],
                    'password' => '12345',
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserCreate.password_min_6',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'permission' => true,
            ],
            'no text password, expected error' => [
                [
                    'password' => ' ',
                    'email' => $faker->email,
                    'roleId' => [2],
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserCreate.password_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'permission' => true,
            ],
            'text password with 6 characters, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [2],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'email field is required, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'roleId' => [2],
                    'email' => ' ',
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'permission' => true,
            ],
            'email field is not unique, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'roleId' => [2],
                    'email' => $emailExistent,
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'permission' => true,
            ],
            'email field is not email valid, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'roleId' => [2],
                    'email' => 'notemail.com',
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_is_valid',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * Método de edição de um usuário.
     *
     * @dataProvider userEditProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_user_edit($parameters, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'edit-user');

        $userExist = User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        $user = User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        $parameters['id'] = $user->id;

        if ($expected_message == 'UserEdit.email_unique') {
            $parameters['email'] = $userExist->email;
        }

        $response = $this->graphQL(
            'userEdit',
            $parameters,
            $this->data,
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
     * @return array
     */
    public function userEditProvider()
    {
        $faker = Faker::create();

        $password = env('PASSWORD_TEST', '123456');
        $userEdit = ['userEdit'];

        return [
            'declare roleId is required, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'UserEdit.role_id_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'permission' => true,
            ],
            'edit user with permission that shouldnt have, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [1, 2],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'PermissionAssignment.validation_message_error',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'permission' => true,
            ],
            'edit user without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [2],
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'permission' => false,
            ],
            'edit user with team, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [2],
                    'positionId' => [2],
                    'teamId' => [1, 2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit user with position, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [2],
                    'positionId' => [2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit user, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'edit user with 2 roles, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [2, 3],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'text password less than 6 characters, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => '12345',
                    'roleId' => [2],
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserEdit.password_min_6',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'permission' => true,
            ],
            'no text password, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => ' ',
                    'email' => $faker->email,
                    'roleId' => [2],
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserEdit.password_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'permission' => true,
            ],
            'text password with 6 characters, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'email field is required, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'email' => ' ',
                    'roleId' => [2],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_required',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'permission' => true,
            ],
            'email field is not unique, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'roleId' => [2],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_unique',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'permission' => true,
            ],
            'email field is not email valid, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'email' => 'notemail.com',
                    'roleId' => [2],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_is_valid',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'permission' => true,
            ],
        ];
    }

    /**
     * Método de deletar um usuário.
     *
     * @dataProvider userDeleteProvider
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function testDeleteUser($data, $type_message_error, $expected_message, $expected, $permission)
    {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'delete-user');

        $user = User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        $parameters['id'] = $user->id;

        if ($data['error'] != null) {
            $parameters['id'] = $data['error'];
        }

        $response = $this->graphQL(
            'userDelete',
            $parameters,
            $this->data,
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
     * @return array
     */
    public function userDeleteProvider()
    {
        $userDelete = ['userDelete'];

        return [
            'delete a user, success' => [
                [
                    'error' => null,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => $userDelete,
                ],
                'permission' => true,
            ],
            'delete user without permission, expected error' => [
                [
                    'error' => null,
                ],
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userDelete,
                ],
                'permission' => false,
            ],
            'delete user that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'type_message_error' => 'message',
                'expected_message' => 'internal',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userDelete,
                ],
                'permission' => true,
            ],
        ];
    }
}
