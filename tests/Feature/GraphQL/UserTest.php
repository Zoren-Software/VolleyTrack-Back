<?php

namespace Tests\Feature\GraphQL;

use App\Models\Position;
use App\Models\Team;
use App\Models\User;
use App\Models\UserInformation;
use Faker\Factory as Faker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    protected $otherUser = false;

    protected $login = true;

    private $role = 'technician';

    public static $data = [
        'id',
        'name',
        'email',
        'emailVerifiedAt',
        'createdAt',
        'updatedAt',
    ];

    private function setPermissions(bool $hasPermission)
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-user');
        $this->checkPermission($hasPermission, $this->role, 'view-user');
    }

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
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        $response = $this->graphQL(
            'users',
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
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $hasPermission,
            $expectedMessage
        );

        if ($hasPermission) {
            $response
                ->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @return array
     */
    public static function listProvider()
    {
        return [
            'with permission' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'users' => [
                            'paginatorInfo' => self::$paginatorInfo,
                            'data' => [
                                '*' => self::$data,
                            ],
                        ],
                    ],
                ],
                'hasPermission' => true,
            ],
            'without permission' => [
                'type_message_error' => 'message',
                'expected_message' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                ],
                'hasPermission' => false,
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
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $user = User::factory()
            ->has(Position::factory()->count(3))
            ->create();
        $user->save();

        $response = $this->graphQL(
            'user',
            [
                'id' => $user->id,
            ],
            self::$data,
            'query',
            false
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $hasPermission,
            $expectedMessage
        );

        if ($hasPermission) {
            $response->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @return array
     */
    public static function infoProvider()
    {
        return [
            'with permission' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'user' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'without permission' => [
                'type_message_error' => 'message',
                'expected_message' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                ],
                'hasPermission' => false,
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
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        if ($hasTeam) {
            $team = Team::factory()->create();
            $parameters['teamId'] = $team->id;
        }

        if($parameters['name'] == null) {
            $parameters['name'] = ' ';
        }

        $response = $this->graphQL(
            'userCreate',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public static function userCreateProvider()
    {
        $faker = Faker::create();
        $emailExistent = $faker->email;
        $cpfExistent = strval($faker->numberBetween(10000000000, 99999999999));
        $rgExistent = strval($faker->numberBetween(10000000000, 99999999999));

        $password = env('PASSWORD_TEST', '123456');

        $userCreate = ['userCreate'];

        return [
            'create user with teams, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [2],
                    'positionId' => [1],
                    'teamId' => [1],
                    'positionId' => [1],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'create user with position, success' => [
                [
                    'name' => $faker->name,
                    'email' => $emailExistent,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'positionId' => [1],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user with non-mandatory parameters, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'cpf' => $cpfExistent,
                    'phone' => $faker->phoneNumber,
                    'rg' => $rgExistent,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'positionId' => [1],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user with cpf existent, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'cpf' => $cpfExistent,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                ],
                'type_message_error' => 'cpf',
                'expected_message' => 'UserCreate.cpf_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user with rg existent, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'rg' => $rgExistent,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                ],
                'type_message_error' => 'rg',
                'expected_message' => 'UserCreate.rg_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'declare roleId is required, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'UserCreate.role_id_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user with 2 roles, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user with permission that shouldnt have, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [1],
                    'password' => $password,
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'PermissionAssignment.validation_message_error',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user without permission, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'password' => $password,
                ],
                'type_message_error' => 'message',
                'expected_message' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => false,
            ],
            'text password less than 6 characters, expected error' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'password' => '12345',
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserCreate.password_min_6',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'no text password, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => ' ',
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserCreate.password_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'text password with 6 characters, success' => [
                [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'password' => $password,
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is required, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => ' ',
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => null,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => $faker->email,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'UserCreate.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'name field is min 3 characters, expected error' => [
                [
                    'name' => 'Th',
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => $faker->email,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'UserCreate.name_min_3',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is not unique, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => $emailExistent,
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is not email valid, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => 'notemail.com',
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserCreate.email_is_valid',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
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
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        $user = User::find($this->user->id);

        if ($hasTeam) {
            $team = Team::factory()->create();
            $parameters['teamId'] = $team->id;
        }

        $parameters['id'] = $user->id;

        if($expectedMessage == 'UserEdit.email_is_valid') {
            $parameters['email'] = 'notemail.com';
        } elseif($expectedMessage != 'UserEdit.email_required') {
            $parameters['email'] = $this->email;
        } else {
            $parameters['email'] = ' ';
        }

        if ($expectedMessage == 'UserEdit.email_unique') {
            $userExist = User::factory()
            ->has(Position::factory()->count(3))
            ->create();

            $parameters['email'] = $userExist->email;
        }

        $response = $this->graphQL(
            'userEdit',
            $parameters,
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public static function userEditProvider()
    {
        $faker = Faker::create();

        $cpfExistent = UserInformation::factory()->create()->cpf;
        $rgExistent = UserInformation::factory()->create()->rg;

        $password = env('PASSWORD_TEST', '123456');
        $userEdit = ['userEdit'];

        return [
            'declare roleId is required, expected error' => [
                [
                    'name' => $faker->name,
                    
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'UserEdit.role_id_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user with permission that shouldnt have, expected error' => [
                [
                    'name' => $faker->name,
                    
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [1],
                ],
                'type_message_error' => 'roleId',
                'expected_message' => 'PermissionAssignment.validation_message_error',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user without permission, expected error' => [
                [
                    'name' => $faker->name,
                    
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'message',
                'expected_message' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => false,
            ],
            'edit user with cpf, rg and phone, success' => [
                [
                    'name' => $faker->name,
                    'cpf' => $cpfExistent,
                    'rg' => $rgExistent,
                    'phone' => $faker->phoneNumber,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'edit user with cpf not unique, expected error' => [
                [
                    'name' => $faker->name,
                    'cpf' => $cpfExistent,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'cpf',
                'expected_message' => 'UserEdit.cpf_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user with rg not unique, expected error' => [
                [
                    'name' => $faker->name,
                    'rg' => $rgExistent,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'rg',
                'expected_message' => 'UserEdit.rg_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user with team, success' => [
                [
                    'name' => $faker->name,
                    
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'positionId' => [2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'edit user with position, success' => [
                [
                    'name' => $faker->name,
                    
                    'password' => $password,
                    'roleId' => [2],
                    'positionId' => [1],
                    'teamId' => [1],
                    'positionId' => [2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user, success' => [
                [
                    'name' => $faker->name,
                    
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user with 2 roles, success' => [
                [
                    'name' => $faker->name,
                    
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2, 3],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'text password less than 6 characters, expected error' => [
                [
                    'name' => $faker->name,
                    
                    'password' => '12345',
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'password',
                'expected_message' => 'UserEdit.password_min_6',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'text password with 6 characters, success' => [
                [
                    'name' => $faker->name,
                    
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is required, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'password' => $password,
                    
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'name',
                'expected_message' => 'UserEdit.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'name field is min 3 characters, expected error' => [
                [
                    'name' => 'Th',
                    'password' => $password,
                    
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'name',
                'expected_message' => 'UserEdit.name_min_3',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is not unique, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is not email valid, expected error' => [
                [
                    'name' => $faker->name,
                    'password' => $password,
                    'email' => 'notemail.com',
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'type_message_error' => 'email',
                'expected_message' => 'UserEdit.email_is_valid',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
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
    public function deleteUser($data, $typeMessageError, $expectedMessage, $expected, $hasPermission)
    {
        $this->setPermissions($hasPermission);

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
            self::$data,
            'mutation',
            false,
            true
        );

        $this->assertMessageError($typeMessageError, $response, $hasPermission, $expectedMessage);

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     * @return array
     */
    public static function userDeleteProvider()
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
                'hasPermission' => true,
            ],
            'delete user without permission, expected error' => [
                [
                    'error' => null,
                ],
                'type_message_error' => 'message',
                'expected_message' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userDelete,
                ],
                'hasPermission' => false,
            ],
            'delete user that does not exist, expected error' => [
                [
                    'error' => 9999,
                ],
                'type_message_error' => 'message',
                'expected_message' => 'internal',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userDelete,
                ],
                'hasPermission' => true,
            ],
        ];
    }

    /**
     * Listar informações de usuário logado.
     *
     * @author Maicon Cerutti
     *
     * @test
     *
     * @dataProvider meProvider
     *
     * @return void
     */
    public function me(
        $typeMessageError,
        $expectedMessage,
        $expected,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        User::factory()
            ->has(Position::factory()->count(3))
            ->has(Team::factory()->count(3))
            ->create();

        $response = $this->graphQL(
            'me',
            [
            ],
            [
                'id',
                'name',
                'email',
                'positions' => [
                    'name',
                ],
                'teams' => [
                    'name',
                ],
            ],
            'query',
            false
        );

        $this->assertMessageError(
            $typeMessageError,
            $response,
            $hasPermission,
            $expectedMessage
        );

        if ($hasPermission) {
            $response
                ->assertJsonStructure($expected)
                ->assertStatus(200);
        }
    }

    /**
     * @return array
     */
    public static function meProvider()
    {
        return [
            'with auth' => [
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'me' => [
                            'id',
                            'name',
                            'email',
                            'positions',
                            'teams',
                        ],
                    ],
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
