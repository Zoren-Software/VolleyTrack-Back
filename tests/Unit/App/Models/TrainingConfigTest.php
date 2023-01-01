<?php

namespace Tests\Unit\App\Models;

use App\Models\TrainingConfig;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tests\TestCase;

class TrainingConfigTest extends TestCase
{
    /**
     * A basic unit test relation config.
     *
     * @return void
     */
    public function test_config()
    {
        $trainingConfig = new TrainingConfig();
        $this->assertInstanceOf(BelongsTo::class, $trainingConfig->config());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    public function test_user()
    {
        $trainingConfig = new TrainingConfig();
        $this->assertInstanceOf(BelongsTo::class, $trainingConfig->user());
    }
}
