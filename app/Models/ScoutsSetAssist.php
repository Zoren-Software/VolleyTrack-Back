<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ScoutsSetAssist extends Model
{
    protected $table = 'scouts_set_assist';

    protected $fillable = [
        'user_id',
        'scout_fundamental_training_id',
        'total_a',
        'total_b',
        'total_c',
        'total',
    ];

    /**
     * @return BelongsTo<User, ScoutsSetAssist>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<ScoutFundamentalTraining, ScoutsSetAssist>
     */
    public function scoutFundamentalTraining(): BelongsTo
    {
        return $this->belongsTo(ScoutFundamentalTraining::class);
    }

    /**
     * Return the total of the scouts set assist.
     * 
     * @return Attribute<int, never>
     */
    public function total(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value + $this->total_a + $this->total_b + $this->total_c,
        );
    }
}
