<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements HasApiTokensContract
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'sanctum';

    public function hasPermissionsViaRoles(string $namePermission, array $permissions): bool
    {
        return in_array($namePermission, $permissions);
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'positions_users')
            ->using(PositionsUsers::class)
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function makePassword($password)
    {
        $this->password = Hash::make($password);
    }

    public function hasPermissionRole(string $namePermission): bool
    {
        return $this->hasPermissionsViaRoles($namePermission, auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray());
    }

    /**
     * @codeCoverageIgnore
     *
     * @return User
     */
    public function deleteUser(int $id): User
    {
        $user = $this->findOrFail($id);
        $user->delete();

        return $user;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['password', 'remember_token', 'token', 'token_sessao', 'updated_at', 'created_at', 'deleted_at'])
            ->dontSubmitEmptyLogs();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'teams_users')
            ->using(TeamsUsers::class)
            ->as('teams')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function createUser($args)
    {
        $user = $this;
        if ($args['id']) {
            $user = $this->findOrFail($args['id']);
        }

        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->makePassword($args['password']);
        $user->save();

        $user->roles()->syncWithoutDetaching($args['roleId']);

        if (isset($args['positionId']) && $user->positions()) {
            $user->positions()->syncWithoutDetaching($args['positionId']);
        }

        if (isset($args['team_id']) && $user->teams()) {
            $user->teams()->syncWithoutDetaching($args['team_id']);
        }

        $user->positions;

        return $user;
    }
}
