<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\TeamMutation;
use App\Models\Team;
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
            $mock->shouldReceive('players')->once()->andReturn($player);
            $mock->shouldReceive('syncWithoutDetaching')->with([$player]);
        });

        $specificFundamentalMutation = new TeamMutation($teamMock);
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
            'send data create, success' => [
                'data' => [
                    'id' => null,
                    'name' => 'Teste',
                    'player_id' => [1],
                    'user_id' => 1,
                ],
                'method' => 'create',
            ],
            'send data edit, success' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'player_id' => [1],
                    'user_id' => 1,
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

        $team
            ->expects($this->exactly($number))
            ->method('deleteTeam')
            ->willReturn($team);

        $teamMutation = new TeamMutation($team);
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
