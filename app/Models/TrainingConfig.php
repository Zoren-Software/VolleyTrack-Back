<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingConfig extends Model
{
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'days_notification',
        'notification_team_by_email',
        'notification_technician_by_email',
    ];

    /**
     * @phpstan-return BelongsTo<Config, TrainingConfig>
     */
    public function config()
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(Config::class);
    }

    /**
     * @return BelongsTo<User, TrainingConfig>
     * @phpstan-return BelongsTo<User, TrainingConfig>
     */
    public function user()
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }
}
