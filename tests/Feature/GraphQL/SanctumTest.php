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
     * Teste da rota de login.
     *
     * @return void
     */
    public function test_login()
    {
        $user = User::factory()->make();
        $user->save();

        $response = $this->graphQL(/** @lang GraphQL */ '
            login(input: {
                email: "' . $user->email . '"
                password: "password"
            }) {
                token
            }
            
        ', 'mutation');

        $response->assertJsonStructure([
            'data' => [
                'login' => [
                    'token'
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

        $response = $this->graphQL(/** @lang GraphQL */ '
            logout {
                status
                message
            }
        ', 'mutation');

        $response->assertJsonStructure([
            'data' => [
                'logout' => [
                    'status',
                    'message'
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

        $response = $this->graphQL(/** @lang GraphQL */ '
            register(
                input: {
                  name: "' . $faker->name . '"
                  email: "' . $faker->email . '"
                  password: "password"
                  password_confirmation: "password"
                }
              ) {
                token
                status
              }
        ', 'mutation');

        $response->assertJsonStructure([
            'data' => [
                'register' => [
                    'token',
                    'status'
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
        $response = $this->graphQL(/** @lang GraphQL */ '
            resendEmailVerification(input: { email: "' . $this->user->email . '" }) {
                status
            }
        ', 'mutation');

        $response->assertJsonStructure([
            'data' => [
                'resendEmailVerification' => [
                    'status'
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

        $response = $this->graphQL(/** @lang GraphQL */ '
            forgotPassword(input: { email: "' . $this->user->email . '" }) {
                status
                message
              }
        ', 'mutation');

        $response->assertJsonStructure([
            'data' => [
                'forgotPassword' => [
                    'status',
                    'message'
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
        $user = User::whereId(2)->first();

        $this->login = true;

        $response = $this->graphQL(/** @lang GraphQL */ '
            updatePassword(
                input: {
                    current_password: "password"
                    password: "password2"
                    password_confirmation: "password2"
                  }
            ) {
                status
            }
        ', 'mutation');

        $response->assertJsonStructure([
            'data' => [
                'updatePassword' => [
                    'status'
                ],
            ],
        ])->assertStatus(200);
    }
}
