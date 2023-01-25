<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\TrainingPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class TrainingPolicyTest extends TestCase
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
    public function permissionCreate(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-training')
            ->willReturn($expected);

        $teamPolicy = new TrainingPolicy();
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
    public function permissionEdit(bool $expected): void
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
     * @test
     *
     * @return void
     */
    public function permissionDelete(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-training')
            ->willReturn($expected);

        $teamPolicy = new TrainingPolicy();
        $teamPolicy->delete($user);
    }

    /**
     * A basic unit test view.
     *
     * @dataProvider permissionProvider
     *
     * @test
     *
     * @return void
     */
    public function permissionView(bool $expected): void
    {
        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($expected) {
            $mock->shouldReceive('hasPermissionTo')
                ->with('edit-training')
                ->andReturn($expected);

            $mock->shouldReceive('hasPermissionTo')
                ->with('view-training')
                ->andReturn($expected);
        });

        $trainingPolicy = new TrainingPolicy();

        $this->assertEquals($expected, $trainingPolicy->view($userMock));
    }
}
