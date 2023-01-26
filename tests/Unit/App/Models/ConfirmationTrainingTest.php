<?php

namespace Tests\Unit\App\Models;

use Tests\TestCase;
use App\Models\ConfirmationTraining;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ConfirmationTrainingTest extends TestCase
{
    /**
     * A basic unit test relation user.
     *
     * @test
     *
     * @return void
     */
    public function user()
    {
        $confirmationTraining = new ConfirmationTraining();
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->user());
    }

    /**
     * A basic unit test relation player.
     *
     * @test
     *
     * @return void
     */
    public function player()
    {
        $confirmationTraining = new ConfirmationTraining();
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->player());
    }

    /**
     * A basic unit test relation training.
     *
     * @test
     *
     * @return void
     */
    public function training()
    {
        $confirmationTraining = new ConfirmationTraining();
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->training());
    }

    /**
     * A basic unit test relation team.
     *
     * @test
     *
     * @return void
     */
    public function team()
    {
        $confirmationTraining = new ConfirmationTraining();
        $this->assertInstanceOf(BelongsTo::class, $confirmationTraining->team());
    }

    /**
     * A basic unit test relation scopeStatus.
     *
     * @test
     *
     * @return void
     */
    public function scopeStatus()
    {
        $confirmationTraining = new ConfirmationTraining();
        
        $this->assertInstanceOf(Builder::class, $confirmationTraining->scopeStatus($confirmationTraining, true));
    }

    /**
     * A basic unit test relation scopePresence.
     *
     * @test
     *
     * @return void
     */
    public function scopePresence()
    {
        $confirmationTraining = new ConfirmationTraining();
        
        $this->assertInstanceOf(Builder::class, $confirmationTraining->scopePresence($confirmationTraining, true));
    }

    /**
     * A basic unit test relation scopePlayer.
     *
     * @test
     *
     * @return void
     */
    public function scopePlayer()
    {
        $confirmationTraining = new ConfirmationTraining();
        
        $this->assertInstanceOf(Builder::class, $confirmationTraining->scopePlayer($confirmationTraining, true));
    }

    /**
     * A basic unit test relation scopeTeam.
     *
     * @test
     *
     * @return void
     */
    public function scopeTeam()
    {
        $confirmationTraining = new ConfirmationTraining();
        
        $this->assertInstanceOf(Builder::class, $confirmationTraining->scopeTeam($confirmationTraining, true));
    }

    /**
     * A basic unit test relation scopeTraining.
     *
     * @test
     *
     * @return void
     */
    public function scopeTraining()
    {
        $confirmationTraining = new ConfirmationTraining();
        
        $this->assertInstanceOf(Builder::class, $confirmationTraining->scopeTraining($confirmationTraining, true));
    }

    /**
     * A basic unit test relation scopeUser.
     *
     * @test
     *
     * @return void
     */
    public function scopeUser()
    {
        $confirmationTraining = new ConfirmationTraining();
        
        $this->assertInstanceOf(Builder::class, $confirmationTraining->scopeUser($confirmationTraining, true));
    }
}
