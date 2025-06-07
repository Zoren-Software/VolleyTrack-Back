<?php

namespace Tests\Unit\App\Models;

use App\Models\Notification;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * A basic unit test list.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function list()
    {
        $notification = new Notification;

        $args = [
            'read' => false,
            'first' => 10,
            'page' => 1,
        ];

        $this->assertInstanceOf(Builder::class, $notification->list($args));
    }

    /**
     * A basic unit test scopeFilterRead.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_filter_read()
    {
        $notification = new Notification;

        $this->assertInstanceOf(Builder::class, $notification->scopeFilterRead($notification, true));
    }

    /**
     * A basic unit test userNotifiable.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user_notifiable()
    {
        $notification = new Notification;

        $this->assertInstanceOf(Builder::class, $notification->userNotifiable());
    }
}
