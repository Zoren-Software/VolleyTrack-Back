<?php

namespace Tests\Unit\App\Rules;

use App\Rules\PermissionAssignment;
use Tests\TestCase;

class PermissionAssignmentTest extends TestCase
{
    /**
     * A basic unit test message.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function message()
    {
        $permissionAssignment = new PermissionAssignment;
        $this->assertIsString($permissionAssignment->message());
    }
}
