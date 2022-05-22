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
     * Método de criação de um time.
     *
     * @dataProvider teamCreateProvider
     * @author Maicon Cerutti
     *
     * @return void
     */
    public function test_team_create($parameters, $type_message_error, $expected_message, $expected)
    {
        $user = User::first();

        $response = $this->graphQL(
            'teamCreate',
            $parameters,
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

        if ($type_message_error) {
            try {
                //code...
                $this->assertSame($response->json()['errors'][0]['extensions']['validation'][$type_message_error][0], trans($expected_message));
            } catch (\Throwable $th) {
                //throw $th;
                dd($response->json());
            }
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
        $faker = Faker::create();
        $userId = 1;
        $nameExistent = $faker->name . ' TEAM';

        return [
            'create team, success' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
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
            'name field is not unique, expected error' => [
                [
                    'name' => $nameExistent,
                    'userId' => $userId,
                ],
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
            'name field is required, expected error' => [
                [
                    'name' => ' ',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TeamCreate.name_required',
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
            'name field is min 3 characteres, expected error' => [
                [
                    'name' => 'AB',
                    'userId' => $userId,
                ],
                'type_message_error' => 'name',
                'expected_message' => 'TeamCreate.name_min',
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
        ];
    }
}
