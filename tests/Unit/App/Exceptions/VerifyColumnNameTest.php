<?php

namespace Tests\Unit\App\Exceptions;

use App\Exceptions\VerifyColumnName;
use Tests\TestCase;

class VerifyColumnNameTest extends TestCase
{
    /**
     * A basic test method render.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function render()
    {
        $exception = new VerifyColumnName;
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
        $exception = new VerifyColumnName;
        $this->assertNull($exception->report());
    }
}
