<?php

namespace Tests\Unit\App\Exceptions;

use App\Exceptions\ConfigPermissionLoadedForDrop;
use Tests\TestCase;

class ConfigPermissionLoadedForDropTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function example()
    {
        $exception = new ConfigPermissionLoadedForDrop;
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
        $exception = new ConfigPermissionLoadedForDrop;
        $this->assertNull($exception->report());
    }
}
