<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ScoutFundamentalTraining;

class ScoutsServe extends Model
{
    protected $table = 'scouts_serve';

    protected $fillable = [
        'user_id',
        'scout_fundamental_training_id',
        'total_a',
        'total_b',
        'total_c',
        'total',
    ];

    /**
     * @return BelongsTo<User, ScoutsServe>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<ScoutFundamentalTraining, ScoutsServe>
     */
    public function scoutFundamentalTraining(): BelongsTo
    {
        return $this->belongsTo(ScoutFundamentalTraining::class);
    }
}