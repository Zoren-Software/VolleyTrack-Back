<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\SpecificFundamentalPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class SpecificFundamentalPolicyTest extends TestCase
{
    /**
     * A basic unit test create.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_create(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-specific-fundamental')
            ->willReturn($expected);

        $specificFundamentalPolicy = new SpecificFundamentalPolicy;
        $specificFundamentalPolicy->create($user);
    }

    /**
     * A basic unit test edit.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_edit(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-specific-fundamental')
            ->willReturn($expected);

        $specificFundamentalPolicy = new SpecificFundamentalPolicy;
        $specificFundamentalPolicy->edit($user);
    }

    /**
     * A basic unit test delete.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_delete(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-specific-fundamental')
            ->willReturn($expected);

        $specificFundamentalPolicy = new SpecificFundamentalPolicy;
        $specificFundamentalPolicy->delete($user);
    }

    /**
     * A basic unit test view.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_view(bool $expected): void
    {
        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($expected) {
            $mock->shouldReceive('hasPermissionTo')
                ->with('edit-specific-fundamental')
                ->andReturn($expected);

            $mock->shouldReceive('hasPermissionTo')
                ->with('view-specific-fundamental')
                ->andReturn($expected);
        });

        $specificFundamentalPolicy = new SpecificFundamentalPolicy;

        $this->assertEquals($expected, $specificFundamentalPolicy->view($userMock));
    }
}
