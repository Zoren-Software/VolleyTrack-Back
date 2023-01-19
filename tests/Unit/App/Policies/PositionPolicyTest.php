<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\PositionPolicy;
use Tests\TestCase;

class PositionPolicyTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @dataProvider permissionProvider
     * @test
     * @return void
     */
    public function create(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('create-position')
            ->willReturn($expected);

        $positionPolicy = new PositionPolicy();
        $positionPolicy->create($user);
    }

    /**
     * A basic unit test edit.
     *
     * @dataProvider permissionProvider
     * @test
     * @return void
     */
    public function edit(bool $expected): void
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
     * @test
     * @return void
     */
    public function deletePositionPolicy(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('delete-position')
            ->willReturn($expected);

        $positionPolicy = new PositionPolicy();
        $positionPolicy->delete($user);
    }
}
