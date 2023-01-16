<?php

namespace Tests\Unit\App\Exceptions;

use App\Exceptions\ConfigPermissionLoaded;
use Tests\TestCase;

class ConfigPermissionLoadedTest extends TestCase
{
    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function render()
    {
        $exception = new ConfigPermissionLoaded();
        $this->assertIsString($exception->render());
    }

    /**
     * A basic test method report.
     * @test
     * @return void
     */
    public function report()
    {
        $exception = new ConfigPermissionLoaded();
        $this->assertNull($exception->report());
    }
}
