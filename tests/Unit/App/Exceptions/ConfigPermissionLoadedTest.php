<?php

namespace Tests\Unit\App\Exceptions;

use App\Exceptions\ConfigPermissionLoaded;
use Tests\TestCase;

class ConfigPermissionLoadedTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function render()
    {
        $exception = new ConfigPermissionLoaded;
        $this->assertIsString($exception->render());
    }

    /**
     * A basic test method report.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function report()
    {
        $exception = new ConfigPermissionLoaded;
        $this->assertNull($exception->report());
    }
}
