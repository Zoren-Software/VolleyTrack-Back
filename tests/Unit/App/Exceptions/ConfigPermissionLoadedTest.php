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
    public function test_example()
    {
        $exception = new ConfigPermissionLoaded();
        $this->assertIsString($exception->render());
    }
}
