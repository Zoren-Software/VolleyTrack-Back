<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\TeamMutation;
use App\Models\Team;
use App\Models\User;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class TeamMutationTest extends TestCase
{
    /**
     * A basic unit test in delete team.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('teamDeleteProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function team_delete($data, $numberFind, $numberDelete)
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

        $teamMutation = new TeamMutation($team);
        $teamMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public static function teamDeleteProvider()
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
