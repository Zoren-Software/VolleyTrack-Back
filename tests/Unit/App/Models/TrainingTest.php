<?php

namespace Tests\Unit\App\Models;

use App\Models\Training;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;

class TrainingTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    public function test_get_activitylog_options()
    {
        $training = new Training();
        $this->assertInstanceOf(LogOptions::class, $training->getActivitylogOptions());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    public function test_relation_user()
    {
        $training = new Training();
        $this->assertInstanceOf(BelongsTo::class, $training->user());
    }

    /**
     * A basic unit test relation team.
     *
     * @return void
     */
    public function test_relation_team()
    {
        $training = new Training();
        $this->assertInstanceOf(BelongsTo::class, $training->team());
    }

    /**
     * A basic unit test relation fundamentals.
     *
     * @return void
     */
    public function test_relation_fundamentals()
    {
        $training = new Training();
        $this->assertInstanceOf(BelongsToMany::class, $training->fundamentals());
    }

    /**
     * A basic unit test relation specificFundamentals.
     *
     * @return void
     */
    public function test_relation_specificFundamentals()
    {
        $training = new Training();
        $this->assertInstanceOf(BelongsToMany::class, $training->specificFundamentals());
    }
}
