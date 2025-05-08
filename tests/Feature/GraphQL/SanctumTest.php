<?php

namespace Tests\Feature\GraphQL;

use App\Models\User;
use App\Models\UserInformation;
use Database\Seeders\Tenants\UserTableSeeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SanctumTest extends TestCase
{
    /**
     * @var bool
     */
    protected $tenancy = true;

    /**
     * @var bool
     */
    protected $graphql = true;

    /**
     * @var bool
     */
    protected $otherUser = true;

    public function setUp(): void
    {
        parent::setUp();
        $this->limparAmbiente();
    }

    public function tearDown(): void
    {
        $this->limparAmbiente();
        parent::tearDown();
    }

    private function limparAmbiente(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Só remove os outros usuários, se necessário
        UserInformation::where('user_id', '!=', $this->user?->id)->forceDelete();
        User::where('id', '!=', $this->user?->id)->forceDelete();

        // Não remova os tokens nem o usuário autenticado
        // DB::table('personal_access_tokens')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Não precisa do seeder se o TestCase já cria o user
    }

    /**
     * Teste da rota de login.
     *
     * @test
     *
     * @return void
     */
    public function login()
    {
        $user = User::factory()->make();
        $user->save();

        $this->graphQL(
            'login',
            [
                'email' => $user->email,
                'password' => 'password',
            ],
            ['token'],
            'mutation',
            true
        )->assertJsonStructure([
            'data' => [
                'login' => [
                    'token',
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Teste da rota de logout.
     *
     * @test
     *
     * @return void
     */
    public function logout()
    {
        $this->login = true;

        $this->graphQL(
            'logout',
            [
                'status',
                'message',
            ],
            [],
            'mutation',
            false
        )->assertJsonStructure([
            'data' => [
                'logout' => [
                    'status',
                    'message',
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Teste da rota de registro de usuários.
     *
     * @test
     *
     * @return void
     */
    public function register()
    {
        $faker = Faker::create();

        $this->graphQL(
            'register',
            [
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ],
            ['token', 'status'],
            'mutation',
            true
        )->assertJsonStructure([
            'data' => [
                'register' => [
                    'token',
                    'status',
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Teste de reenvio de email de verificação.
     *
     * @test
     *
     * @return void
     */
    public function resendEmailVerification()
    {
        $this->graphQL(
            'resendEmailVerification',
            [
                'email' => $this->user->email,
            ],
            ['status'],
            'mutation',
            true
        )->assertJsonStructure([
            'data' => [
                'resendEmailVerification' => [
                    'status',
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Teste de reenvio de email para recuperar senha.
     *
     * @test
     *
     * @return void
     */
    public function forgotPassword()
    {
        $this->login = true;

        $this->graphQL(
            'forgotPassword',
            [
                'email' => $this->user->email,
            ],
            ['status', 'message'],
            'mutation',
            false,
            true
        )->assertJsonStructure([
            'data' => [
                'forgotPassword' => [
                    'status',
                    'message',
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Teste de reenvio de email para atualizar senha.
     *
     * @test
     *
     * @return void
     */
    public function updatePassword()
    {
        $this->login = true;

        $this->graphQL(
            'updatePassword',
            [
                'current_password' => 'password',
                'password' => 'password2',
                'password_confirmation' => 'password2',
            ],
            ['status'],
            'mutation',
            true
        )->assertJsonStructure([
            'data' => [
                'updatePassword' => [
                    'status',
                ],
            ],
        ])->assertStatus(200);
    }
}
