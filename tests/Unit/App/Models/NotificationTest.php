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
}
