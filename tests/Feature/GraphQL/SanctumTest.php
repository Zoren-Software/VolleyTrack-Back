<?php

namespace Tests\Feature\GraphQL;

use App\Models\User;
use Faker\Factory as Faker;
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

    /**
     * Teste da rota de login.
     *
     * @return void
     */
    public function test_login()
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
     * @return void
     */
    public function test_logout()
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
     * @return void
     */
    public function test_register()
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
     * @return void
     */
    public function test_resend_email_verification()
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
     * @return void
     */
    public function test_forgot_password()
    {
        $this->login = true;

        $this->graphQL(
            'forgotPassword',
            [
                'email' => $this->user->email,
            ],
            ['status', 'message'],
            'mutation',
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
     * @return void
     */
    public function test_update_password()
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
