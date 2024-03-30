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
     * @test
     *
     * @return void
     */
    public function list()
    {
        $notification = new Notification();

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
     * @test
     *
     * @return void
     */
    public function scopeFilterRead()
    {
        $notification = new Notification();

        $this->assertInstanceOf(Builder::class, $notification->scopeFilterRead($notification, true));
    }

    /**
     * A basic unit test userNotifiable.
     *
     * @test
     *
     * @return void
     */
    public function userNotifiable()
    {
        $notification = new Notification();

        $this->assertInstanceOf(Builder::class, $notification->userNotifiable());
    }
}
