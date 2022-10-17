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
     *
     * @return void
     */
    public function test_language()
    {
        $config = new Config();
        $this->assertInstanceOf(BelongsTo::class, $config->language());
    }

    /**
     * A basic unit test relation trainingConfig.
     *
     * @return void
     */
    public function test_trainingConfig()
    {
        $config = new Config();
        $this->assertInstanceOf(HasOne::class, $config->trainingConfig());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    public function test_user()
    {
        $config = new Config();
        $this->assertInstanceOf(BelongsTo::class, $config->user());
    }
}
