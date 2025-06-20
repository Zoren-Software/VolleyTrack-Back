<?php

namespace Tests\Feature\GraphQL;

use App\Models\NotificationType;
use App\Models\Position;
use App\Models\PositionsUsers;
use App\Models\Team;
use App\Models\TeamsUsers;
use App\Models\Training;
use App\Models\User;
use App\Models\UserInformation;
use Database\Seeders\Tenants\PositionTableSeeder;
use Database\Seeders\Tenants\UserTableSeeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected bool $graphql = true;

    protected bool $tenancy = true;

    protected bool $otherUser = false;

    protected bool $login = true;

    /**
     * @var string
     */
    private $role = 'technician';

    /**
     * @var array<int, string>
     */
    public static $data = [
        'id',
        'name',
        'email',
        'emailVerifiedAt',
        'createdAt',
        'updatedAt',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->limparAmbiente();
    }

    protected function tearDown(): void
    {
        $this->limparAmbiente();

        parent::tearDown();
    }

    private function limparAmbiente(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        UserInformation::truncate();
        Training::truncate();
        TeamsUsers::truncate();
        Team::truncate();
        PositionsUsers::truncate();
        User::where('id', '>', 5)->forceDelete();
        Position::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seed([
            UserTableSeeder::class,
            PositionTableSeeder::class,
        ]);
    }

    private function setPermissions(bool $hasPermission): void
    {
        $this->checkPermission($hasPermission, $this->role, 'edit-user');
        $this->checkPermission($hasPermission, $this->role, 'view-user');
    }

    /**
     * Listagem de todos os usuários.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $expected
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('listProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function users_list(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ): void {
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
     * @return array<string, array<string, mixed>>
     */
    public static function listProvider(): array
    {
        return [
            'with permission' => [
                'typeMessageError' => false,
                'expectedMessage' => false,
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
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
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
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('infoProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function user_info(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
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
     * @return array<string, array<string, mixed>>
     */
    public static function infoProvider(): array
    {
        return [
            'with permission' => [
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'user' => self::$data,
                    ],
                ],
                'hasPermission' => true,
            ],
            'without permission' => [
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
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
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('userCreateProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function user_create(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasTeam,
        bool $hasPermission
    ) {
        $this->setPermissions($hasPermission);

        if ($expectedMessage === 'UserCreate.cpf_unique') {
            // Cria um usuário com o mesmo CPF informado nos parâmetros
            $existingUser = User::factory()->create();
            $existingUser->information()->update([
                'cpf' => $parameters['cpf'],
            ]);
        }

        if ($parameters['password'] == 'testing.password_test') {
            $parameters['password'] = config('testing.password_test');
        }

        if ($hasTeam) {
            $team = Team::factory()->create();
            $parameters['teamId'] = $team->id;
        }

        if ($parameters['name'] == null) {
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

        if ($expectedMessage === false) {
            // NOTE - Verifica se o usuário foi criado no banc o de dados
            $user = User::where('email', $parameters['email'])->firstOrFail();
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'name' => $parameters['name'],
                'email' => $parameters['email'],
            ]);

            // NOTE - Verifica se o usuário foi criado com os registros default em notification_settings
            $notificationsTypes = NotificationType::where('is_active', true)->get();

            foreach ($notificationsTypes as $type) {
                $this->assertDatabaseHas('notification_settings', [
                    'user_id' => $user->id,
                    'notification_type_id' => $type->id,
                    'via_email' => false,
                    'via_system' => $type->allow_system,
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public static function userCreateProvider(): array
    {
        $faker = Faker::create();
        $emailExistent = $faker->email;
        $cpfExistent = strval($faker->numberBetween(10000000000, 99999999999));
        $rgExistent = strval($faker->numberBetween(10000000000, 99999999999));

        $password = 'testing.password_test';

        $userCreate = ['userCreate'];

        return [
            'create user with notification email, success' => [
                [
                    'sendEmailNotification' => true,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [2],
                    'positionId' => [1],
                    'teamId' => [1],
                    'password' => $password,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'create user with teams, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'roleId' => [2],
                    'positionId' => [1],
                    'teamId' => [1],
                    'password' => $password,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
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
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $emailExistent,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'password' => $password,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'create user with non-mandatory parameters, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'cpf' => $cpfExistent,
                    'phone' => $faker->phoneNumber,
                    'rg' => $rgExistent,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'password' => $password,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'create user with cpf existent, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'cpf' => $cpfExistent,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                ],
                'typeMessageError' => 'cpf',
                'expectedMessage' => 'UserCreate.cpf_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user with rg existent, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'rg' => $rgExistent,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                ],
                'typeMessageError' => 'rg',
                'expectedMessage' => 'UserCreate.rg_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'declare roleId is required, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [],
                ],
                'typeMessageError' => 'roleId',
                'expectedMessage' => 'UserCreate.role_id_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'create user with birth date, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                    'birthDate' => $faker->date(),
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'create user with 2 roles, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [3],
                    'password' => $password,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'create user with permission that shouldnt have, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [1],
                    'password' => $password,
                ],
                'typeMessageError' => 'roleId',
                'expectedMessage' => 'PermissionAssignment.validation_message_error',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'create user without permission, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'password' => $password,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => false,
            ],
            'text password less than 6 characters, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'password' => '12345',
                ],
                'typeMessageError' => 'password',
                'expectedMessage' => 'UserCreate.password_min_6',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'text password with 6 characters, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'password' => $password,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userCreate' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'email field is required, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => ' ',
                ],
                'typeMessageError' => 'email',
                'expectedMessage' => 'UserCreate.email_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => null,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => $faker->email,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'UserCreate.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'name field is min 3 characters, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => 'Th',
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => $faker->email,
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'UserCreate.name_min_3',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is not unique, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => $emailExistent,
                ],
                'typeMessageError' => 'email',
                'expectedMessage' => 'UserCreate.email_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userCreate,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is not email valid, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                    'email' => 'notemail.com',
                ],
                'typeMessageError' => 'email',
                'expectedMessage' => 'UserCreate.email_is_valid',
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
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('userEditProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function user_edit(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasTeam,
        bool $hasPermission
    ): void {
        $this->setPermissions($hasPermission);

        $this->assertNotNull($this->user);

        /** @var User $user */
        $user = User::findOrFail($this->user->id);

        if ($parameters['password'] == 'testing.password_test') {
            $parameters['password'] = config('testing.password_test');
        }

        if (isset($parameters['cpf']) && $parameters['cpf']) {
            $parameters['cpf'] = User::factory()->create()->information->cpf;
        } elseif (isset($parameters['cpf']) && !$parameters['cpf']) {
            $faker = Faker::create();
            $parameters['cpf'] = (string) $faker->numberBetween(10000000000, 99999999999);
        }

        if (isset($parameters['rg']) && $parameters['rg']) {
            $parameters['rg'] = User::factory()->create()->information->rg;
        } elseif (isset($parameters['rg']) && !$parameters['rg']) {
            $faker = Faker::create();
            $parameters['rg'] = (string) $faker->numberBetween(100000000, 999999999);
        }

        if ($hasTeam) {
            $team = Team::factory()->create();
            $parameters['teamId'] = $team->id;
        }

        $parameters['id'] = $user->id;

        if ($expectedMessage == 'UserEdit.email_is_valid') {
            $parameters['email'] = 'notemail.com';
        } elseif ($expectedMessage != 'UserEdit.email_required') {
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function userEditProvider(): array
    {
        $faker = Faker::create();

        $password = 'testing.password_test';
        $userEdit = ['userEdit'];

        return [
            'declare roleId is required, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [],
                ],
                'typeMessageError' => 'roleId',
                'expectedMessage' => 'UserEdit.role_id_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user with permission that shouldnt have, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [1],
                ],
                'typeMessageError' => 'roleId',
                'expectedMessage' => 'PermissionAssignment.validation_message_error',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user without permission, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => false,
            ],
            'edit user with cpf, rg and phone, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'cpf' => false,
                    'rg' => false,
                    'phone' => $faker->phoneNumber,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
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
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'cpf' => true,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'cpf',
                'expectedMessage' => 'UserEdit.cpf_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user with rg not unique, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'rg' => true,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'rg',
                'expectedMessage' => 'UserEdit.rg_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'edit user with team, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
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
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'roleId' => [2],
                    'teamId' => [1],
                    'positionId' => [2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'edit user, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'edit user with birth date, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'birthDate' => $faker->date(),
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'edit user with 2 roles, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2, 3],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'text password less than 6 characters, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => '12345',
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'password',
                'expectedMessage' => 'UserEdit.password_min_6',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'text password with 6 characters, success' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userEdit' => self::$data,
                    ],
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'email field is required, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'email',
                'expectedMessage' => 'UserEdit.email_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => true,
                'hasPermission' => true,
            ],
            'name field is required, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => ' ',
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'UserEdit.name_required',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'name field is min 3 characters, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => 'Th',
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'name',
                'expectedMessage' => 'UserEdit.name_min_3',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is not unique, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'email',
                'expectedMessage' => 'UserEdit.email_unique',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userEdit,
                ],
                'hasTeam' => false,
                'hasPermission' => true,
            ],
            'email field is not email valid, expected error' => [
                [
                    'sendEmailNotification' => false,
                    'name' => $faker->name,
                    'password' => $password,
                    'email' => 'notemail.com',
                    'positionId' => [1],
                    'teamId' => [1],
                    'roleId' => [2],
                ],
                'typeMessageError' => 'email',
                'expectedMessage' => 'UserEdit.email_is_valid',
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
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $expected
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('userDeleteProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function delete_user(
        array $data,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ): void {
        $this->setPermissions($hasPermission);

        /** @var \App\Models\User $user */
        $user = User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        /** @var array<string, mixed> $parameters */
        $parameters = [];

        $parameters['id'] = $user->id;

        if ($data['error'] != null) {
            $parameters['id'] = $data['error'];
        }

        if (!$this->user) {
            $this->fail('User não inicializado');
        }

        if ($expectedMessage == 'UserDelete.cannot_delete_own_account') {
            $parameters['id'] = $this->user->id;
        }

        if ($expectedMessage == 'UserDelete.ids_exists') {
            $maxId = User::max('id');
            $parameters['id'] = (is_int($maxId) ? $maxId : 0) + 1;
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function userDeleteProvider(): array
    {
        $userDelete = ['userDelete'];

        return [
            'delete a user, success' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => $userDelete,
                ],
                'hasPermission' => true,
            ],
            'delete user without permission, expected error' => [
                [
                    'error' => null,
                ],
                'typeMessageError' => 'message',
                'expectedMessage' => self::$unauthorized,
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
                'typeMessageError' => 'id',
                'expectedMessage' => 'UserDelete.ids_exists',
                'expected' => [
                    'errors' => self::$errors,
                    'data' => $userDelete,
                ],
                'hasPermission' => true,
            ],
            'delete user can not delete own account, expected error' => [
                [
                    'error' => 'this',
                ],
                'typeMessageError' => 'id',
                'expectedMessage' => 'UserDelete.cannot_delete_own_account',
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
     * @param  array<string, mixed>  $expected
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('meProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function me(
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function meProvider(): array
    {
        return [
            'with auth' => [
                'typeMessageError' => false,
                'expectedMessage' => false,
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

    /**
     * Método de criar senha para um usuário.
     *
     * @author Maicon Cerutti
     *
     * @param  array<string, mixed>  $parameters
     * @param  array<string, mixed>  $expected
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('setPasswordProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function set_password(
        array $parameters,
        string|bool $typeMessageError,
        string|bool $expectedMessage,
        array $expected,
        bool $hasPermission
    ): void {
        $this->setPermissions($hasPermission);

        $user = User::factory()
            ->has(Position::factory()->count(3))
            ->create();

        if (isset($parameters['email']) && $parameters['email'] === true) {
            $parameters['email'] = $user->email;
        } elseif (isset($parameters['email']) && $parameters['email'] === false) {
            unset($parameters['email']);
        } elseif (isset($parameters['email']) && $parameters['email'] === 'not_valid') {
            $parameters['email'] = 'notemail.com';
        }

        if (isset($parameters['token']) && $parameters['token'] === true) {
            $parameters['token'] = $user->set_password_token;
        } elseif (isset($parameters['token']) && $parameters['token'] === 'not_find_user_invalid_token') {
            $parameters['token'] = 'not_find_user_invalid_token';
        } else {
            unset($parameters['token']);
        }

        if (isset($parameters['password']) && $parameters['password'] === true) {
            $parameters['password'] = config('testing.password_test');
        } elseif (isset($parameters['password']) && $parameters['password'] === 'min_6') {
            $parameters['password'] = '1234';
        } else {
            unset($parameters['password']);
        }

        if (isset($parameters['passwordConfirmation']) && $parameters['passwordConfirmation'] === true) {
            $parameters['passwordConfirmation'] = config('testing.password_test');
        } elseif (isset($parameters['passwordConfirmation']) && $parameters['passwordConfirmation'] === 'not_match') {
            $parameters['passwordConfirmation'] = '12345678';
        } else {
            unset($parameters['passwordConfirmation']);
        }

        $response = $this->graphQL(
            'userSetPassword',
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
     * @return array<string, array<int|string, mixed>>
     */
    public static function setPasswordProvider(): array
    {
        $userSetPassword = ['userSetPassword'];

        return [
            'set password a user, success' => [
                [
                    'email' => true,
                    'token' => true,
                    'password' => true,
                    'passwordConfirmation' => true,
                ],
                'typeMessageError' => false,
                'expectedMessage' => false,
                'expected' => [
                    'data' => [
                        'userSetPassword' => [
                            'id',
                            'name',
                            'email',
                            'emailVerifiedAt',
                            'createdAt',
                            'updatedAt',
                        ],
                    ],
                ],
                'hasPermission' => true,
            ],
            'set password a user, not send email, error' => [
                [
                    'email' => false,
                    'token' => true,
                    'password' => true,
                    'passwordConfirmation' => true,
                ],
                'typeMessageError' => 'email',
                'expectedMessage' => 'UserSetPassword.email_required',
                'expected' => [
                    'data' => $userSetPassword,
                ],
                'hasPermission' => true,
            ],
            'set password a user, not valid email, error' => [
                [
                    'email' => 'not_valid',
                    'token' => true,
                    'password' => true,
                    'passwordConfirmation' => true,
                ],
                'typeMessageError' => 'email',
                'expectedMessage' => 'UserSetPassword.email_is_valid',
                'expected' => [
                    'data' => $userSetPassword,
                ],
                'hasPermission' => true,
            ],
            'set password a user, not send token, error' => [
                [
                    'email' => true,
                    'token' => false,
                    'password' => true,
                    'passwordConfirmation' => true,
                ],
                'typeMessageError' => 'token',
                'expectedMessage' => 'UserSetPassword.token_required',
                'expected' => [
                    'data' => $userSetPassword,
                ],
                'hasPermission' => true,
            ],
            'set password a user, not token string, error' => [
                [
                    'email' => true,
                    'token' => 'not_find_user_invalid_token',
                    'password' => true,
                    'passwordConfirmation' => true,
                ],
                'typeMessageError' => 'token',
                'expectedMessage' => 'UserSetPassword.token_exists',
                'expected' => [
                    'data' => $userSetPassword,
                ],
                'hasPermission' => true,
            ],
            'set password a user, not send password, error' => [
                [
                    'email' => true,
                    'token' => true,
                    'password' => false,
                    'passwordConfirmation' => true,
                ],
                'typeMessageError' => 'password',
                'expectedMessage' => 'UserSetPassword.password_required',
                'expected' => [
                    'data' => $userSetPassword,
                ],
                'hasPermission' => true,
            ],
            'set password a user, send password min 6 characters, error' => [
                [
                    'email' => true,
                    'token' => true,
                    'password' => 'min_6',
                    'passwordConfirmation' => true,
                ],
                'typeMessageError' => 'password',
                'expectedMessage' => 'UserSetPassword.password_min_6',
                'expected' => [
                    'data' => $userSetPassword,
                ],
                'hasPermission' => true,
            ],
            'set password a user, send password does not match, error' => [
                [
                    'email' => true,
                    'token' => true,
                    'password' => true,
                    'passwordConfirmation' => 'not_match',
                ],
                'typeMessageError' => 'passwordConfirmation',
                'expectedMessage' => 'UserSetPassword.password_confirmation_same',
                'expected' => [
                    'data' => $userSetPassword,
                ],
                'hasPermission' => true,
            ],
            'set password a user, not send passwordConfirmation, error' => [
                [
                    'email' => true,
                    'token' => true,
                    'password' => true,
                    'passwordConfirmation' => false,
                ],
                'typeMessageError' => 'passwordConfirmation',
                'expectedMessage' => 'UserSetPassword.password_confirmation_required',
                'expected' => [
                    'data' => $userSetPassword,
                ],
                'hasPermission' => true,
            ],
        ];
    }
}
