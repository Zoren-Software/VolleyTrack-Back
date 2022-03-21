<?php

namespace Tests\Feature\GraphQL;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    protected $graphql = false;
    protected $tenancy = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_list()
    {
        User::factory()->make();
        $response = $this->graphQL(/** @lang GraphQL */ '
            {
                users {
                  paginatorInfo {
                    count
                    currentPage
                    firstItem
                    hasMorePages
                    lastItem
                    lastPage
                    perPage
                    total
                  }
                  data {
                    id
                    name
                    email
                    email_verified_at
                    created_at
                    updated_at
                  }
                }
              }
        ')->assertJsonStructure([
            'data' => [
                'users' => [
                    'paginatorInfo' =>
                    [
                        'count',
                        'currentPage',
                        'firstItem',
                        'hasMorePages',
                        'lastItem',
                        'lastPage',
                        'perPage',
                        'total',
                    ],
                ],
            ],
        ]);
    }
}
