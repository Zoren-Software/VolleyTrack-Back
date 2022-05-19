<?php

namespace Tests\Feature\GraphQL;

use App\Models\Team;
use App\Models\User;
use Faker\Factory as Faker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    protected $graphql = true;

    protected $tenancy = true;

    /**
     * Listagem de um time
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_team_info()
    {
        $team = Team::factory()->make();
        $team->save();

        $saida = [
            'id',
            'name',
            'createdAt',
            'updatedAt'
        ];

        $response = $this->graphQL(
            'team',
            [
                'id' => $team->id,
            ],
            $saida,
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'team' => $saida,
            ],
        ])->assertStatus(200);
    }

    /**
     * Listagem de todos os times.
     *
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_teams_list()
    {
        Team::factory()->make()->save();

        $response = $this->graphQL(
            'teams',
            [
                'name' => '%%',
                'first' => 10,
                'page' => 1,
            ],
            [
                'paginatorInfo' => [
                    'count',
                    'currentPage',
                    'firstItem',
                    'hasMorePages',
                    'lastItem',
                    'lastPage',
                    'perPage',
                    'total'
                ],
                'data' => [
                    'id',
                    'name',
                    'createdAt',
                    'updatedAt',
                ],
            ],
            'query',
            false
        );

        $response->assertJsonStructure([
            'data' => [
                'teams' => [
                    'paginatorInfo' => [
                        'count',
                        'currentPage',
                        'firstItem',
                        'hasMorePages',
                        'lastItem',
                        'lastPage',
                        'perPage',
                        'total',
                    ],
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'createdAt',
                            'updatedAt'
                        ]
                    ]
                ],
            ],
        ])->assertStatus(200);
    }

    /**
     * Método de criação de um time.
     *
     * @dataProvider teamCreateProvider
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_team_create($name, $type_message_error, $expected_message, $expected)
    {
        $user = User::first();

        $response = $this->graphQL(
            'teamCreate',
            [
                'name' => $name,
                'userId' => $user->id,
            ],
            [
                'id',
                'name',
                'userId',
                'createdAt',
                'updatedAt'
            ],
            'mutation',
            false,
            true
        );

        dd($response);

        if ($type_message_error) {
            $this->assertSame($response->json()['errors'][0]['extensions']['validation'][$type_message_error][0], trans($expected_message));
        }

        $response
            ->assertJsonStructure($expected)
            ->assertStatus(200);
    }

    /**
     *
     * @return Array
     */
    public function teamCreateProvider()
    {
        $faker = User::factory()->make();
        $faker->save();
        $nameExistent = $faker->name;

        return [
            'name field is not unique, expected error' => [
                'name' => $nameExistent,
                'type_message_error' => 'name',
                'expected_message' => 'TeamCreate.name_unique',
                'expected' => [
                    'errors' => [
                        '*' => [
                            'message',
                            'locations',
                            'extensions',
                            'path',
                            'trace'
                        ]
                    ],
                    'data' => [
                        'teamCreate'
                    ]
                ],
            ],
            'create team, success' => [
                'name' => $faker->name . ' TEAM',
                'type_message_error' => false,
                'expected_message' => false,
                'expected' => [
                    'data' => [
                        'teamCreate' => [
                            'id',
                            'name',
                            'userId',
                            'createdAt',
                            'updatedAt'
                        ],
                    ],
                ],
            ],
        ];
    }
}
