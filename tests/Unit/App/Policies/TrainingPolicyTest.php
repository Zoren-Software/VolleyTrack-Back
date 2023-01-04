<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\TrainingPolicy;
use Tests\TestCase;

class TrainingPolicyTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @dataProvider permissionProvider
     *
     * @return void
     */
    public function test_create(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('create-training')
            ->willReturn($expected);

        $teamPolicy = new TrainingPolicy();
        $teamPolicy->create($user);
    }

    /**
     * A basic unit test edit.
     *
     * @dataProvider permissionProvider
     *
     * @return void
     */
    public function test_edit(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-training')
            ->willReturn($expected);

        $teamPolicy = new TrainingPolicy();
        $teamPolicy->edit($user);
    }

    /**
     * A basic unit test delete.
     *
     * @dataProvider permissionProvider
     *
     * @return void
     */
    public function test_delete(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('delete-training')
            ->willReturn($expected);

        $teamPolicy = new TrainingPolicy();
        $teamPolicy->delete($user);
    }
}
