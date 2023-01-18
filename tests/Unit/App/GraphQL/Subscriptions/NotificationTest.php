<?php

namespace Tests\Unit\App\GraphQL\Subscriptions;

use Tests\TestCase;
use App\GraphQL\Subscriptions\Notification;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Illuminate\Http\Request;

class NotificationTest extends TestCase
{
    /**
     * A basic test method authorize.
     * @test
     * @return void
     */
    public function authorize()
    {

        $subscriberMock = $this->createMock(Subscriber::class);
        $requestMock = $this->createMock(Request::class);

        $notification = new Notification();

        $this->assertTrue($notification->authorize($subscriberMock, $requestMock));
    }

    /**
     * A basic test method filter.
     * @test
     * @return void
     */
    public function filter()
    {
        $subscriberMock = $this->createMock(Subscriber::class);
        $requestMock = $this->createMock(Request::class);

        $notification = new Notification();

        $this->assertTrue($notification->filter($subscriberMock, $requestMock));
    }
}
