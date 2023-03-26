<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\TrainingConfigPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class TrainingConfigPolicyTest extends TestCase
{
    /**
     * A basic unit test edit.
     *
     * @test
     *
     * @dataProvider permissionProvider
     */
    public function permissionEdit(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-training-config')
            ->willReturn($expected);

        $trainingPolicy = new TrainingConfigPolicy();
        $trainingPolicy->edit($user);
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
                ->with('edit-training-config')
                ->andReturn($expected);

            $mock->shouldReceive('hasPermissionTo')
                ->with('view-training-config')
                ->andReturn($expected);
        });

        $trainingConfigPolicy = new TrainingConfigPolicy();

        $this->assertEquals($expected, $trainingConfigPolicy->view($userMock));
    }
}
