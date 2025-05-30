<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TeamsUsers extends Pivot
{
    use LogsActivity;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $table = 'teams_users';

    /**
     * @param User|null $user
     */
    public function __construct(?User $user = null)
    {
        $this->user = $user ?? new User;
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

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

    /**
     * @return void
     */
    public function updateRoleInRelationship()
    {
        if ($this->user->find($this->user_id)->hasRole('technician')) {
            $this->role = 'technician';
        }
    }
}
