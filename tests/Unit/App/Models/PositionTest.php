<?php

namespace Tests\Unit\App\Models;

use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class PositionTest extends TestCase
{
    /**
     * A basic unit test relation users.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function users()
    {
        $position = new Position;
        $this->assertInstanceOf(BelongsToMany::class, $position->users());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user()
    {
        $position = new Position;
        $this->assertInstanceOf(BelongsTo::class, $position->user());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $position = new Position;
        $this->assertInstanceOf(LogOptions::class, $position->getActivitylogOptions());
    }
}
