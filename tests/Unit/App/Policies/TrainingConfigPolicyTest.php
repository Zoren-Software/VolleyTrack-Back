<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\TrainingConfigPolicy;
use Tests\TestCase;

class TrainingConfigPolicyTest extends TestCase
{
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
            ->with('edit-training-config')
            ->willReturn($expected);

        $trainingPolicy = new TrainingConfigPolicy();
        $trainingPolicy->edit($user);
    }
}
