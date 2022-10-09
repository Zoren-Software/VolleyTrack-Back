<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Training extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'team_id',
        'user_id',
        'name',
        'description',
        'status',
        'date_start',
        'date_end',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function fundamentals()
    {
        return $this->belongsToMany(Fundamental::class, 'fundamentals_trainings')
            ->using(FundamentalsTrainings::class)
            ->as('fundamentals')
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
