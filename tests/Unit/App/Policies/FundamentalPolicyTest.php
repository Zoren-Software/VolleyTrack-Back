<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\FundamentalPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class FundamentalPolicyTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function create(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-fundamental')
            ->willReturn($expected);

        $fundamentalPolicy = new FundamentalPolicy;
        $fundamentalPolicy->create($user);
    }

    /**
     * A basic unit test edit.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_edit(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-fundamental')
            ->willReturn($expected);

        $fundamentalPolicy = new FundamentalPolicy;
        $fundamentalPolicy->edit($user);
    }

    /**
     * A basic unit test delete.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_delete(bool $expected): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('hasPermissionTo')
            ->with('edit-fundamental')
            ->willReturn($expected);

        $fundamentalPolicy = new FundamentalPolicy;
        $fundamentalPolicy->delete($user);
    }

    /**
     * A basic unit test view.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_view(bool $expected): void
    {
        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($expected) {
            $mock->shouldReceive('hasPermissionTo')
                ->with('edit-fundamental')
                ->andReturn($expected);

            $mock->shouldReceive('hasPermissionTo')
                ->with('view-fundamental')
                ->andReturn($expected);
        });

        $fundamentalPolicy = new FundamentalPolicy;
        $return = $fundamentalPolicy->view($userMock);

        $this->assertEquals($expected, $return);
    }
}
