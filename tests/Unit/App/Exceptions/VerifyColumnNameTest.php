<?php

namespace Tests\Unit\App\Exceptions;

use App\Exceptions\VerifyColumnName;
use Tests\TestCase;

class VerifyColumnNameTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $exception = new VerifyColumnName();
        $this->assertIsString($exception->render());
    }
}
