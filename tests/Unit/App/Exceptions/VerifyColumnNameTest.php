<?php

namespace Tests\Unit\App\Exceptions;

use App\Exceptions\VerifyColumnName;
use Tests\TestCase;

class VerifyColumnNameTest extends TestCase
{
    /**
     * A basic test method render.
     *
     * @test
     *
     * @return void
     */
    public function render()
    {
        $exception = new VerifyColumnName();
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
        $exception = new VerifyColumnName();
        $this->assertNull($exception->report());
    }
}
