<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\TeamMutation;
use App\Models\Team;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use PHPUnit\Framework\TestCase;

class TeamMutationTest extends TestCase
{
    /**
     * A basic unit test in method create.
     *
     * @return void
     */
    public function test_team_create()
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $team = $this->createMock(Team::class);

        $team->expects($this->once())
            ->method('save');

        $teamMutation = new TeamMutation($team);
        $teamMutation->create(null, [
            'name' => 'Teste',
            'user_id' => 1,
        ], $graphQLContext);
    }

    /**
     * A basic unit test in method edit.
     *
     * @return void
     */
    public function test_team_edit()
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $team = $this->createMock(Team::class);

        $team->expects($this->once())
            ->method('save');

        $teamMutation = new TeamMutation($team);
        $teamMutation->edit(null, [
            'id' => 1,
            'name' => 'Teste',
            'user_id' => 1,
        ], $graphQLContext);
    }

    /**
     * A basic unit test in delete team.
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
            ]
        ];
    }
}
