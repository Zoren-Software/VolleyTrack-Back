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
    public function test_example()
    {
        $exception = new ConfigPermissionLoadedForDrop();
        $this->assertIsString($exception->render());
    }
}
