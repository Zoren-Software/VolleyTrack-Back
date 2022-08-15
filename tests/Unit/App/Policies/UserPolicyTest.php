<?php

namespace Tests\Unit\App\Policies;

use Tests\TestCase;
use App\Policies\UserPolicy;
use App\Models\User;

class UserPolicyTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @dataProvider createProvider
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
                false
            ],
        ];
    }

    /**
     * A basic unit test edit.
     *
     * @dataProvider editProvider
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

    public function editProvider(): array
    {
        return [
            'when permission allows' => [
                true,
            ],
            'when permission does not allow' => [
                false
            ],
        ];
    }
}
