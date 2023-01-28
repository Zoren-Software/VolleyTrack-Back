<?php

namespace Tests\Unit\App\Policies;

use App\Models\User;
use App\Policies\ConfirmationTrainingPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class ConfirmationTrainingPolicyTest extends TestCase
{
    /**
     * A basic unit test view.
     *
     * @dataProvider permissionProvider
     *
     * @test
     *
     * @return void
     */
    public function permissionView(bool $expected): void
    {
        $userMock = $this->mock(User::class, function (MockInterface $mock) use ($expected) {
            $mock->shouldReceive('hasPermissionTo')
                ->with('view-confirmation-training')
                ->andReturn($expected);
        });

        $confirmationTrainingPolicy = new ConfirmationTrainingPolicy();

        $this->assertEquals($expected, $confirmationTrainingPolicy->view($userMock));
    }
}
