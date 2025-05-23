<?php

namespace Tests\Unit\App\Models;

use App\Models\TeamsUsers;
use App\Models\User;
use Mockery\MockInterface;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class TeamsUsersTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $teamsUsers = new TeamsUsers;
        $this->assertInstanceOf(LogOptions::class, $teamsUsers->getActivitylogOptions());
    }

    /**
     * A basic unit test update role in relationship.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('updateRoleInRelationshipProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function update_role_in_relationship($data)
    {
        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('find')
                ->once()
                ->with(1)
                ->andReturn($mock);
            $mock->shouldReceive('hasRole')
                ->with('technician')
                ->once()->andReturn($data['user_relation_team_technian']);
        });

        $teamsUsers = new TeamsUsers($userMock);
        $teamsUsers->user_id = 1;
        $teamsUsers->updateRoleInRelationship();

        if ($data['user_relation_team_technian']) {
            $this->assertEquals('technician', $teamsUsers->role);
        } else {
            $this->assertEquals(null, $teamsUsers->role);
        }
    }

    public static function updateRoleInRelationshipProvider()
    {
        return [
            'updating role in teams relationship with users having permission' => [
                'data' => [
                    'user_relation_team_technian' => true,
                ],
            ],
            'updating role in teams relationship with users not having permission' => [
                'data' => [
                    'user_relation_team_technian' => false,
                ],
            ],
        ];
    }
}
