<?php

namespace Tests\Unit\App\Models;

use App\Models\Config;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    /**
     * A basic unit test relation language.
     * @test
     * @return void
     */
    public function language()
    {
        $config = new Config();
        $this->assertInstanceOf(BelongsTo::class, $config->language());
    }

    /**
     * A basic unit test relation trainingConfig.
     * @test
     * @return void
     */
    public function trainingConfig()
    {
        $config = new Config();
        $this->assertInstanceOf(HasOne::class, $config->trainingConfig());
    }

    /**
     * A basic unit test relation user.
     * @test
     * @return void
     */
    public function user()
    {
        $config = new Config();
        $this->assertInstanceOf(BelongsTo::class, $config->user());
    }
}
