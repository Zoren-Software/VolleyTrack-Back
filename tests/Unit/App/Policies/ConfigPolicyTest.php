<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\ConfigPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class ConfigPolicyTest extends TestCase
{
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
            ->with('edit-config')
            ->willReturn($expected);

        $configPolicy = new ConfigPolicy;
        $configPolicy->edit($user);
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
                ->with('edit-config')
                ->andReturn($expected);

            $mock->shouldReceive('hasPermissionTo')
                ->with('view-config')
                ->andReturn($expected);
        });

        $configPolicy = new ConfigPolicy;

        $this->assertEquals($expected, $configPolicy->view($userMock));
    }
}
