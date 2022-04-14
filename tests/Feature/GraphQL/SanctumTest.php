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
     * A basic feature test example.
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
}
