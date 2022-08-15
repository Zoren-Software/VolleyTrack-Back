<?php

namespace Tests\Unit\App\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class TeamTest extends TestCase
{
    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    public function test_user()
    {
        $position = new Team();
        $this->assertInstanceOf(BelongsTo::class, $position->user());
    }
}
