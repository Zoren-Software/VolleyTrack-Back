<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\SpecificFundamentalPolicy;
use Tests\TestCase;

class SpecificFundamentalPolicyTest extends TestCase
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
            ->with('create-specific-fundamental')
            ->willReturn($expected);

        $specificFundamentalPolicy = new SpecificFundamentalPolicy();
        $specificFundamentalPolicy->create($user);
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
            ->with('edit-specific-fundamental')
            ->willReturn($expected);

        $specificFundamentalPolicy = new SpecificFundamentalPolicy();
        $specificFundamentalPolicy->edit($user);
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
            ->with('delete-specific-fundamental')
            ->willReturn($expected);

        $specificFundamentalPolicy = new SpecificFundamentalPolicy();
        $specificFundamentalPolicy->delete($user);
    }
}
