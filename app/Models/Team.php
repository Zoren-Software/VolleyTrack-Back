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

    /**
     * @codeCoverageIgnore
     *
     * @return Team
     */
    public function deleteTeam(int $id): Team
    {
        $team = $this->findOrFail($id);
        $team->delete();

        return $team;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at', 'created_at', 'deleted_at'])
            ->dontSubmitEmptyLogs();
    }

    public function players()
    {
        return $this->belongsToMany(User::class, 'teams_users')
            ->using(TeamsUsers::class)
            ->as('players')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }
}
