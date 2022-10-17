<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingConfig extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'days_notification',
        'notification_team_by_email',
        'notification_technician_by_email',
    ];

    public function config()
    {
        return $this->belongsTo(Config::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
