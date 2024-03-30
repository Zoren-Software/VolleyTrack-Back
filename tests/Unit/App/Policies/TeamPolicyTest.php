<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\TeamPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class TeamPolicyTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @dataProvider permissionProvider
     *
     * @test
     */
    public function permissionCreate(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-team')
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
     */
    public function permissionEdit(bool $expected): void
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
     */
    public function permissionDelete(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-team')
            ->willReturn($expected);

        $teamPolicy = new TeamPolicy();
        $teamPolicy->delete($user);
    }

    /**
     * A basic unit test view.
     *
     * @dataProvider permissionProvider
     *
     * @test
     */
    public function permissionView(bool $expected): void
    {
        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($expected) {
            $mock->shouldReceive('hasPermissionTo')
                ->with('edit-team')
                ->andReturn($expected);

            $mock->shouldReceive('hasPermissionTo')
                ->with('view-team')
                ->andReturn($expected);
        });

        $teamPolicy = new TeamPolicy();

        $this->assertEquals($expected, $teamPolicy->view($userMock));
    }
}
