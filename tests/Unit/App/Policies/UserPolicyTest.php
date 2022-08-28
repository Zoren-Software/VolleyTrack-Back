<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\UserPolicy;
use Tests\TestCase;

class UserPolicyTest extends TestCase
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
            ->with('create-user')
            ->willReturn($expected);

        $userPolicy = new UserPolicy();
        $userPolicy->create($user);
    }

    public function createProvider(): array
    {
        return [
            'when permission allows' => [
                true,
            ],
            'when permission does not allow' => [
                false,
            ],
        ];
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
            ->with('edit-user')
            ->willReturn($expected);

        $userPolicy = new UserPolicy();
        $userPolicy->edit($user);
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
            ->with('delete-user')
            ->willReturn($expected);

        $userPolicy = new UserPolicy();
        $userPolicy->delete($user);
    }
}
