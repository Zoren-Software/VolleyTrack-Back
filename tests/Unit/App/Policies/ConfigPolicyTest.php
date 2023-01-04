<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\ConfigPolicy;
use Tests\TestCase;

class ConfigPolicyTest extends TestCase
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
            ->with('edit-config')
            ->willReturn($expected);

        $fundamentalPolicy = new ConfigPolicy();
        $fundamentalPolicy->edit($user);
    }
}
