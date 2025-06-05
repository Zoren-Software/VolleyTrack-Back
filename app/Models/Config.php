<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Config extends Model
{
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name_tenant',
        'language_id',
    ];

    /**
     * @return BelongsTo<Language, Config>
     */
    public function language(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(Language::class);
    }

    /**
     * @return HasOne<TrainingConfig, Config>
     */
    public function trainingConfig(): HasOne
    {
        /** @phpstan-ignore-next-line */
        return $this->hasOne(TrainingConfig::class);
    }

    /**
     * @return BelongsTo<User, Config>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }
}
