<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\PositionPolicy;
use PHPUnit\Framework\TestCase;

class PositionPolicyTest extends TestCase
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
            ->willReturn($expected);

        $positionPolicy = new PositionPolicy($user);

        $this->assertEquals($expected, $positionPolicy->create());
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
            ->willReturn($expected);

        $positionPolicy = new PositionPolicy($user);

        $this->assertEquals($expected, $positionPolicy->edit());
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
