<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\TeamMutation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class TeamMutationTest extends TestCase
{
    /**
     * A basic unit test create and edit team.
     *
     * @dataProvider teamProvider
     *
     * @test
     *
     * @return void
     */
    public function teamMake($data, $method)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $teamMock = $this->mock(Team::class, function (MockInterface $mock) use ($data, $method) {
            $player = $this->createMock(BelongsToMany::class);

            if ($data['id']) {
                $mock->shouldReceive('find')
                    ->once()
                    ->with($data['id'])
                    ->andReturn($mock);
            }

            $mock->shouldReceive($method)->with($data)->once()->andReturn($mock);
            $mock->shouldReceive('players')->times(2)->andReturn($player);
            $mock->shouldReceive('syncWithPivotValues')->with($data['player_id'], ['role' => 'technician']);
        });

        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($data) {
            if ($data['id']) {
                $mock->shouldReceive('find')
                    ->once()
                    ->with($data['id'])
                    ->andReturn($mock);
            }

            $mock->shouldReceive('find')
                ->times($data['number_return_find'])
                ->with(1)
                ->andReturn($mock);

            $mock->shouldReceive('hasRole')
                ->with('Jogador')
                ->once()->andReturn($data['user_relation_team_technian']);
        });

        $specificFundamentalMutation = new TeamMutation($teamMock, $userMock);
        $teamMockReturn = $specificFundamentalMutation->make(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($teamMock, $teamMockReturn);
    }

    public function teamProvider()
    {
        return [
            'send data create with user relation team, success' => [
                'data' => [
                    'id' => null,
                    'name' => 'Teste',
                    'player_id' => [1],
                    'user_id' => 1,
                    'user_relation_team_technian' => true,
                    'number_return_find' => 1,
                    'number_return_hasRole' => 0,
                ],
                'method' => 'create',
            ],
            'send data create not with user relation team, success' => [
                'data' => [
                    'id' => null,
                    'name' => 'Teste',
                    'player_id' => [1],
                    'user_id' => 1,
                    'user_relation_team_technian' => false,
                    'number_return_find' => 1,
                    'number_return_hasRole' => 1,
                ],
                'method' => 'create',
            ],
            'send data edit with user relation team, success' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'player_id' => [1],
                    'user_id' => 1,
                    'user_relation_team_technian' => true,
                    'number_return_find' => 0,
                    'number_return_hasRole' => 0,
                ],
                'method' => 'update',
            ],
            'send data edit not with user relation team, success' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'player_id' => [1],
                    'user_id' => 1,
                    'user_relation_team_technian' => false,
                    'number_return_find' => 0,
                    'number_return_hasRole' => 0,
                ],
                'method' => 'update',
            ],

        ];
    }

    /**
     * A basic unit test in delete team.
     *
     * @dataProvider teamDeleteProvider
     *
     * @test
     *
     * @return void
     */
    public function teamDelete($data, $numberFind, $numberDelete)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $team = $this->mock(Team::class, function (MockInterface $mock) use ($numberFind, $numberDelete, $data) {
            $mock->shouldReceive('findOrFail')
                ->times($numberFind)
                ->with(1)
                ->andReturn($mock);

            if (count($data) > 1) {
                $mock->shouldReceive('findOrFail')
                    ->times($numberFind)
                    ->with(2)
                    ->andReturn($mock);
            }

            $mock->shouldReceive('delete')
                ->times($numberDelete)
                ->andReturn(true);
        });

        $user = $this->createMock(User::class);

        $teamMutation = new TeamMutation($team, $user);
        $teamMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public function teamDeleteProvider()
    {
        return [
            'send data delete, success' => [
                'data' => [1],
                'numberFind' => 1,
                'numberDelete' => 1,
            ],
            'send data delete multiple teams, success' => [
                'data' => [1, 2],
                'numberFind' => 1,
                'numberDelete' => 2,
            ],
            'send data delete no items, success' => [
                'data' => [],
                'numberFind' => 0,
                'numberDelete' => 0,
            ],
        ];
    }
}
