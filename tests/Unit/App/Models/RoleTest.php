<?php

namespace Tests\Unit\App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * A basic unit test booted.
     *
     * @return void
     */
    public function test_booted()
    {
        $builder = $this->createMock(Builder::class);
        $builder->expects($this->once())
            ->method('when')
            ->willReturnSelf();
        Role::booted($builder);
    }
}
