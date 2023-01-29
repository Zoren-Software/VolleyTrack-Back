<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\NotificationMutation;
use App\Models\Notification;
use App\Models\User;
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
        $graphQLContext = $this->mock(GraphQLContext::class, function ($mock) {
            $userMock = $this->mock(User::class, function ($mock) {
                $mock->shouldReceive('getAttribute')
                    ->with('id')
                    ->andReturn(1);
            });
            $mock->shouldReceive('user')
                ->andReturn($userMock);
        });

        $userMock = $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('find')
                ->andReturnSelf();
            
            $mock->shouldReceive('unreadNotifications')
                ->andReturnSelf();
            
            $mock->shouldReceive('update')
                ->once()
                ->with(['read_at' => Carbon::now()->toDateTimeString()])
                ->andReturnSelf();
        });

        

        $notificationMutation = new NotificationMutation($userMock);
        $notificationMockReturn = $notificationMutation->notificationsRead(
            null,
            $data,
            $graphQLContext
        );

        $this->assertIsArray($notificationMockReturn);
        $this->assertArrayHasKey('message', $notificationMockReturn);
        $this->assertEquals(trans('NotificationRead.read_all_notifications'), $notificationMockReturn['message']);

        
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
