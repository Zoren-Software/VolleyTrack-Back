<?php

namespace Tests\Unit\App\Policies;

use Tests\TestCase;
use App\Policies\TeamPolicy;
use App\Models\User;

class TeamPolicyTest extends TestCase
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
            ->with('create-team')
            ->willReturn($expected);

        $teamPolicy = new TeamPolicy();
        $teamPolicy->create($user);
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
            ->with('edit-team')
            ->willReturn($expected);

        $teamPolicy = new TeamPolicy();
        $teamPolicy->edit($user);
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
