<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ScoutsDefense extends Model
{
    protected $table = 'scouts_defense';

    protected $fillable = [
        'user_id',
        'scout_fundamental_training_id',
        'total_a',
        'total_b',
        'total_c',
        'total',
    ];

    /**
     * @return BelongsTo<User, ScoutsDefense>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<ScoutFundamentalTraining, ScoutsDefense>
     */
    public function scoutFundamentalTraining(): BelongsTo
    {
        return $this->belongsTo(ScoutFundamentalTraining::class);
    }

    /**
     * Return the total of the scouts defense.
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
