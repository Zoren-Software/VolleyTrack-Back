<?php

namespace Tests\Unit\App\Exceptions;

use App\Exceptions\ConfigPermissionLoadedForDrop;
use Tests\TestCase;

class ConfigPermissionLoadedForDropTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @test
     *
     * @return void
     */
    public function example()
    {
        $exception = new ConfigPermissionLoadedForDrop();
        $this->assertIsString($exception->render());
    }

    /**
     * A basic test method report.
     *
     * @test
     *
     * @return void
     */
    public function report()
    {
        $exception = new ConfigPermissionLoadedForDrop();
        $this->assertNull($exception->report());
    }
}
