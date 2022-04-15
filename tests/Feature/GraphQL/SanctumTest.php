<?php

namespace Tests\Feature\GraphQL;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Faker\Factory as Faker;

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
        $user = User::first();
        
        $response = $this->graphQL(/** @lang GraphQL */ '
            login(input: {
                email: "'.$user->email.'"
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
}
