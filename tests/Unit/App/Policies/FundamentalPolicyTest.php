<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\FundamentalPolicy;
use Tests\TestCase;

class FundamentalPolicyTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @test
     *
     * @dataProvider permissionProvider
     *
     * @return void
     */
    public function create(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('create-fundamental')
            ->willReturn($expected);

        $fundamentalPolicy = new FundamentalPolicy();
        $fundamentalPolicy->create($user);
    }

    /**
     * A basic unit test edit.
     *
     * @test
     *
     * @dataProvider permissionProvider
     *
     * @return void
     */
    public function edit(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-fundamental')
            ->willReturn($expected);

        $fundamentalPolicy = new FundamentalPolicy();
        $fundamentalPolicy->edit($user);
    }

    /**
     * A basic unit test delete.
     *
     * @dataProvider permissionProvider
     * @test
     *
     * @return void
     */
    public function deleteFundamentalPolicy(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('delete-fundamental')
            ->willReturn($expected);

        $fundamentalPolicy = new FundamentalPolicy();
        $fundamentalPolicy->delete($user);
    }
}
