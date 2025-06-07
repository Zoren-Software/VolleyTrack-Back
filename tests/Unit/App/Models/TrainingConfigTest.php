<?php

namespace Tests\Unit\App\Models;

use App\Models\TrainingConfig;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class TrainingConfigTest extends TestCase
{
    /**
     * A basic unit test relation config.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function config()
    {
        $trainingConfig = new TrainingConfig;
        $this->assertInstanceOf(BelongsTo::class, $trainingConfig->config());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user()
    {
        $trainingConfig = new TrainingConfig;
        $this->assertInstanceOf(BelongsTo::class, $trainingConfig->user());
    }
}
