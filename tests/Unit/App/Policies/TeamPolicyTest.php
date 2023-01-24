<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\TeamPolicy;
use Tests\TestCase;

class TeamPolicyTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @dataProvider permissionProvider
     *
     * @test
     *
     * @return void
     */
    public function create(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('create-team')
            ->willReturn($expected);

        $teamPolicy = new TeamPolicy();
        $teamPolicy->create($user);
    }

    /**
     * A basic unit test edit.
     *
     * @dataProvider permissionProvider
     *
     * @test
     *
     * @return void
     */
    public function edit(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-team')
            ->willReturn($expected);

        $teamPolicy = new TeamPolicy();
        $teamPolicy->edit($user);
    }

    /**
     * A basic unit test delete.
     *
     * @dataProvider permissionProvider
     *
     * @test
     *
     * @return void
     */
    public function deleteTeamPolicy(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('delete-team')
            ->willReturn($expected);

        $teamPolicy = new TeamPolicy();
        $teamPolicy->delete($user);
    }
}
