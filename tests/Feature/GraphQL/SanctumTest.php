<?php

namespace Tests\Feature\GraphQL;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

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
        $user = User::first();

        // Testar rota que precisa de autenticação
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
}
