<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
