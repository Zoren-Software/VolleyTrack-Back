<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasApiTokensContract
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rolesCustom(): BelongsToMany
    {
        $relation = $this->morphToMany(
            Role::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            PermissionRegistrar::$pivotRole
        );

        if (!PermissionRegistrar::$teams) {
            return $relation;
        }

        return $relation->wherePivot(PermissionRegistrar::$teamsKey, getPermissionsTeamId())
            ->where(function ($q) {
                $teamField = config('permission.table_names.roles') . '.' . PermissionRegistrar::$teamsKey;
                $q->whereNull($teamField)->orWhere($teamField, getPermissionsTeamId());
            });
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
        return $this->hasPermissionsViaRoles(
            $namePermission,
            auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray()
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(
                [
                    'password',
                    'remember_token',
                    'token',
                    'token_sessao',
                    'updated_at',
                    'created_at',
                    'deleted_at',
                ]
            )
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

    /**
     * @codeCoverageIgnore
     */
    public function hasRoleTechnician(): bool
    {
        return $this->hasRole('technician');
    }

    /**
     * @codeCoverageIgnore
     */
    public function hasRolePlayer(): bool
    {
        return $this->hasRole('player');
    }

    /**
     * @codeCoverageIgnore
     */
    public function hasRoleAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function playerConfirmationsTraining()
    {
        return $this->hasMany(ConfirmationTraining::class, 'player_id');
    }

    public function userConfirmationsTraining()
    {
        return $this->hasMany(ConfirmationTraining::class, 'user_id');
    }

    /**
     * @codeCoverageIgnore
     */
    public function me()
    {
        return $this->with(
            'positions',
            'teams',
        )
            ->find(auth()->user()->id);
    }

    public function information()
    {
        return $this->hasOne(UserInformation::class);
    }

    /**
     * @codeCoverageIgnore
     *
     * @param  mixed  $args
     * @return void
     */
    public function updateOrNewInformation($args)
    {
        $attributes = [];

        if (isset($args['cpf'])) {
            $attributes['cpf'] = $args['cpf'];
        }

        if (isset($args['phone'])) {
            $attributes['phone'] = $args['phone'];
        }

        if (isset($args['rg'])) {
            $attributes['rg'] = $args['rg'];
        }

        if (isset($args['birthDate'])) {
            $attributes['birth_date'] = $args['birthDate'];
        }

        if (isset($args['birth_date'])) {
            $attributes['birth_date'] = $args['birth_date'];
        }

        if (!empty($attributes)) {
            if (!$this->information()->exists()) {
                $this->information()->create($attributes);
            } else {
                $this->information->update($attributes);
            }
        }
    }

    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterPosition($args)
            ->filterTeam($args);
    }

    public function scopeFilterSearch(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['search']), function ($query) use ($args) {
            $query
            ->where(function ($query) use ($args) {
                $query
                    ->filterName($args['filter']['search'])
                    ->filterEmail($args['filter']['search'])
                    ->filterUserInformation($args['filter']['search'])
                    ->filterPositionName($args['filter']['search'])
                    ->filterTeamName($args['filter']['search']);
            });
        });
    }

    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->where('users.name', 'like', $search);
        });
    }

    public function scopeFilterEmail(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->orWhere('users.email', 'like', $search);
        });
    }

    public function scopeFilterUserInformation(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->orWhereHas('information', function ($query) use ($search) {
                $query->filter($search);
            });
        });
    }

    public function scopeFilterPositionName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->orWhereHas('positions', function ($query) use ($search) {
                $query->filterName($search);
            });
        });
    }

    public function scopeFilterTeamName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->orWhereHas('teams', function ($query) use ($search) {
                $query->filterName($search);
            });
        });
    }

    public function scopeFilterPosition(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['positionsIds']) &&
            !empty($args['filter']['positionsIds']),
            function ($query) use ($args) {
                $query->whereHas('positions', function ($query) use ($args) {
                    $query->filterIds($args['filter']['positionsIds']);
                });
            }
        );
    }

    public function scopeFilterTeam(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['teamsIds']) &&
            !empty($args['filter']['teamsIds']),
            function ($query) use ($args) {
                $query->whereHas('teams', function ($query) use ($args) {
                    $query->filterIds($args['filter']['teamsIds']);
                });
            }
        );
    }

    public function scopeFilterIds(Builder $query, array $ids)
    {
        $query->when(isset($ids) && !empty($ids), function ($query) use ($ids) {
            $query->whereIn('users.id', $ids);
        });
    }

    public function scopeFilterIgnores(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('users.id', $args['filter']['ignoreIds']);
        });
    }

    public function saveLastUserChange()
    {
        $this->user_id = auth()->user()->id ?? null;
        $this->save();
    }
}
