<?php

namespace Tests\Unit\GraphQL\Mutations;

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
}
