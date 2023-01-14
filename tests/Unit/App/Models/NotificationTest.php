<?php

namespace Tests\Unit\App\Models;

use Tests\TestCase;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Mockery\MockInterface;

class NotificationTest extends TestCase
{
    /**
     * A basic unit test example.
     * @test
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
