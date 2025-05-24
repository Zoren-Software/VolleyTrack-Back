<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\ConfirmationTrainingPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class ConfirmTrainingPolicyTest extends TestCase
{
    /**
     * A basic unit test view.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_view(bool $expected): void
    {
        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($expected) {
            $mock->shouldReceive('hasPermissionTo')
                ->with('view-confirmation-training')
                ->andReturn($expected);
        });

        $confirmationTrainingPolicy = new ConfirmationTrainingPolicy;

        $this->assertEquals($expected, $confirmationTrainingPolicy->view($userMock));
    }

    /**
     * A basic unit test confirmTraining.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_confirm_training(bool $expected): void
    {
        $args = [
            'player_id' => 1,
        ];

        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($args, $expected) {
            $mock->shouldReceive('hasRoleAdmin')
                ->andReturn($expected);

            $mock->shouldReceive('hasRoleTechnician')
                ->andReturn($expected);

            $mock->shouldReceive('getAttribute')
                ->with('id')
                ->andReturn($args['player_id']);
        });

        $confirmationTrainingPolicy = new ConfirmationTrainingPolicy;

        $this->assertEquals(true, $confirmationTrainingPolicy->confirmTraining($userMock, $args));
    }

    /**
     * A basic unit test view.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('permissionProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function permission_confirm_presence(bool $expected): void
    {
        $args = [
            'player_id' => 1,
        ];

        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($expected) {
            $mock->shouldReceive('hasRoleAdmin')
                ->andReturn($expected);

            $mock->shouldReceive('hasRoleTechnician')
                ->andReturn($expected);
        });

        $confirmationTrainingPolicy = new ConfirmationTrainingPolicy;

        $this->assertEquals($expected, $confirmationTrainingPolicy->confirmPresence($userMock, $args));
    }
}
