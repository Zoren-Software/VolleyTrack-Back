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
     * @dataProvider createProvider
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
            ->with('edit-specific-fundamental')
            ->willReturn($expected);

        $specificFundamentalPolicy = new SpecificFundamentalPolicy();
        $specificFundamentalPolicy->edit($user);
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
