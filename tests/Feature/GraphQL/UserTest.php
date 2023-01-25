<?php

namespace Tests\Feature\GraphQL;

use App\Models\Position;
use App\Models\Team;
use App\Models\User;
use Faker\Factory as Faker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $otherUser = false;

    private $permission = 'technician';

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
     * @test
     *
     * @dataProvider listProvider
     *
     * @return void
     */
    public function usersList(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $permission
    ) {
        $this->login = true;

        User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        $this->checkPermission($permission, $this->permission, 'edit-user');
        $this->checkPermission($permission, $this->permission, 'view-user');

        $response = $this->graphQL(
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
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $permission,
            $expectedMessage
        );

        if ($permission) {
            $response
                ->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @return array
     */
    public function listProvider()
    {
        return [
            'with permission' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'users' => [
                            'paginatorInfo' => $this->paginatorInfo,
                            'data' => [
                                '*' => $this->data,
                            ],
                        ],
                    ],
                ],
                'permission' => true,
            ],
            'without permission' => [
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                ],
                'permission' => false,
            ],
        ];
    }

    /**
     * Listagem de um usuário
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @dataProvider infoProvider
     *
     * @return void
     */
    public function userInfo(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $permission
    ) {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'edit-user');
        $this->checkPermission($permission, $this->permission, 'view-user');

        $user = User::factory()
            ->has(Position::factory()->count(3))
            ->create();
        $user->save();

        $response = $this->graphQL(
            'user',
            [
                'id' => $user->id,
            ],
            $this->data,
            'query',
            false
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $permission,
            $expectedMessage
        );

        if ($permission) {
            $response->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @return array
     */
    public function infoProvider()
    {
        return [
            'with permission' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'user' => $this->data,
                    ],
                ],
                'permission' => true,
            ],
            'without permission' => [
                'type_message_error' => 'message',
                'expected_message' => $this->unauthorized,
                'expected' => [
                    'errors' => $this->errors,
                ],
                'permission' => false,
            ],
        ];
    }

    /**
     * Método de criação de um usuário.
     *
     * @dataProvider userCreateProvider
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @return void
     */
    public function userCreate(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasTeam,
        bool $permission
        ) {
        $this->login = true;

        $faker = Faker::create();

        if ($hasTeam) {
            $team = Team::factory()->create();
            $parameters['teamId'] = $team->id;
        }

        $this->checkPermission($permission, $this->permission, 'edit-user');

        $parameters['name'] = $faker->name;

        $response = $this->graphQL(
            'userCreate',
            $parameters,
            $this->data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $permission, $expectedMessage);

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
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => $this->data,
                    ],
                ],
                'hasTeam' => true,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
                'permission' => true,
            ],
            'create user with 2 roles, success' => [
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
                'hasTeam' => false,
                'permission' => true,
            ],
            'create user with permission that shouldnt have, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [1],
                    'password' => $password,
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'PermissionAssignment.validation_message_error',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
     * @test
     *
     * @return void
     */
    public function userEdit(
        $parameters,
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasTeam,
        bool $permission
        ) {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'edit-user');

        $userExist = User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        $user = User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        if ($hasTeam) {
            $team = Team::factory()->create();
            $parameters['teamId'] = $team->id;
        }

        $parameters['id'] = $user->id;

        if ($expectedMessage == 'UserEdit.email_unique') {
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

        $this->assertMessageError($typeMessageError, $response, $permission, $expectedMessage);

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
                'hasTeam' => false,
                'permission' => true,
            ],
            'edit user with permission that shouldnt have, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'roleId' => [1],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'PermissionAssignment.validation_message_error',
                'expected' => [
                    'errors' => $this->errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
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
                'hasTeam' => false,
                'permission' => false,
            ],
            'edit user with team, success' => [
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
                'hasTeam' => true,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
                'hasTeam' => false,
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
     * @test
     *
     * @return void
     */
    public function testDeleteUser($data, $typeMessageError, $expectedMessage, $expected, $permission)
    {
        $this->login = true;

        $this->checkPermission($permission, $this->permission, 'edit-user');

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

        $this->assertMessageError($typeMessageError, $response, $permission, $expectedMessage);

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
