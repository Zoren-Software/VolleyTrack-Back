<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\PositionPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class PositionPolicyTest extends TestCase
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
            ->with('edit-position')
            ->willReturn($expected);

        $positionPolicy = new PositionPolicy();
        $positionPolicy->create($user);
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
            ->with('edit-position')
            ->willReturn($expected);

        $positionPolicy = new PositionPolicy();
        $positionPolicy->edit($user);
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
            ->with('edit-position')
            ->willReturn($expected);

        $positionPolicy = new PositionPolicy();
        $positionPolicy->delete($user);
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
                ->with('edit-position')
                ->andReturn($expected);

            $mock->shouldReceive('hasPermissionTo')
                ->with('view-position')
                ->andReturn($expected);
        });

        $positionPolicy = new PositionPolicy();

        $this->assertEquals($expected, $positionPolicy->view($userMock));
    }
}
