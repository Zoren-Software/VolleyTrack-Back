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
    #[\PHPUnit\Framework\Attributes\Test]
    public function language()
    {
        $config = new Config;
        $this->assertInstanceOf(BelongsTo::class, $config->language());
    }

    /**
     * A basic unit test relation trainingConfig.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function training_config()
    {
        $config = new Config;
        $this->assertInstanceOf(HasOne::class, $config->trainingConfig());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user()
    {
        $config = new Config;
        $this->assertInstanceOf(BelongsTo::class, $config->user());
    }
}
