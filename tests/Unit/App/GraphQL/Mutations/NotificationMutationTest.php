<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\NotificationMutation;
use App\Models\Notification;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;
use Carbon\Carbon;

class NotificationMutationTest extends TestCase
{
    /**
     * A test for notificationRead method.
     * 
     * @test
     *
     * @dataProvider confirmationTrainingProvider
     *
     * @return void
     */
    public function notificationRead($data)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);

        $notificationMock = $this->mock(Notification::class, function ($mock) use ($data) {
            $mock->shouldReceive('find')
                ->once()
                ->with($data['id'])
                ->andReturnSelf();

            $mock->shouldReceive('update')
                ->once()
                ->with(['read_at' => Carbon::now()->toDateTimeString()])
                ->andReturnSelf();
        });

        $notificationMutation = new NotificationMutation($notificationMock);
        $notificationMockReturn = $notificationMutation->notificationRead(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($notificationMock, $notificationMockReturn);
    }

    public function confirmationTrainingProvider()
    {
        return [
            'confirming notification reading , success' => [
                'data' => [
                    'id' => 1,
                ],
            ],
        ];
    }
}
