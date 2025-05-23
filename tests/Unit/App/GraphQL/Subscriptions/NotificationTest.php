<?php

namespace Tests\Unit\App\GraphQL\Subscriptions;

use App\GraphQL\Subscriptions\Notification;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * A basic test method authorize.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function authorize()
    {
        $subscriberMock = $this->createMock(Subscriber::class);
        $requestMock = $this->createMock(Request::class);

        $notification = new Notification;

        $this->assertTrue($notification->authorize($subscriberMock, $requestMock));
    }

    /**
     * A basic test method filter.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function filter()
    {
        $subscriberMock = $this->createMock(Subscriber::class);
        $requestMock = $this->createMock(Request::class);

        $notification = new Notification;

        $this->assertTrue($notification->filter($subscriberMock, $requestMock));
    }
}
