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
     * @return void
     */
    public function test_team_make($data, $method)
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
     * @return void
     */
    public function test_team_delete($data, $number)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $team = $this->createMock(Team::class);
        $user = $this->createMock(User::class);

        $team
            ->expects($this->exactly($number))
            ->method('deleteTeam')
            ->willReturn($team);

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
            'send array, success' => [
                [1],
                1,
            ],
            'send multiple itens in array, success' => [
                [1, 2, 3],
                3,
            ],
            'send empty array, success' => [
                [],
                0,
            ],
        ];
    }
}
