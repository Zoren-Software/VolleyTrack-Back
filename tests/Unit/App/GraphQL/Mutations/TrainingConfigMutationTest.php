<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\TrainingConfigMutation;
use App\Models\TrainingConfig;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class TrainingConfigMutationTest extends TestCase
{
    /**
     * A basic unit test make.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function make()
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $configMock = $this->mock(TrainingConfig::class, function (MockInterface $mock) {
            $mock->shouldReceive('find')
                ->once()
                ->andReturn($mock);

            $mock->shouldReceive('update')->once()->andReturn($mock);
        });

        $data = [
            'user_id' => 1,
            'days_notification' => 1,
            'notification_team_by_email' => true,
            'notification_technician_by_email' => true,
        ];

        $configMutation = new TrainingConfigMutation($configMock);
        $configMockReturn = $configMutation->make(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($configMock, $configMockReturn);
    }
}
