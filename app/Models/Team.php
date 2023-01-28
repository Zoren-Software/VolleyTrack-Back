<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmationsTraining()
    {
        return $this->hasMany(ConfirmationTraining::class);
    }

    public function technicians()
    {
        return $this->belongsToMany(User::class, 'teams_users')
            ->using(TeamsUsers::class)
            ->as('technicians')
            ->where('teams_users.role', 'technician')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function players()
    {
        return $this->belongsToMany(User::class, 'teams_users')
            ->using(TeamsUsers::class)
            ->as('players')
            ->where('teams_users.role', 'player')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(
                [
                    'updated_at',
                    'created_at',
                    'deleted_at',
                ]
            )
            ->dontSubmitEmptyLogs();
    }
}
