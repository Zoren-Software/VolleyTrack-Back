<?php

namespace App\Models;

use App\Mail\User\ConfirmEmailAndCreatePasswordMail;
use App\Mail\User\ForgotPasswordMail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property \Illuminate\Notifications\DatabaseNotificationCollection<string, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property \Illuminate\Notifications\DatabaseNotificationCollection<string, \Illuminate\Notifications\DatabaseNotification> $unreadNotifications
 * @property \App\Models\UserInformation $information
 * @property \Illuminate\Database\Eloquent\Collection<array-key, \App\Models\NotificationSetting> $notificationSettings
 * @property string|null $remember_token
 */
class User extends Authenticatable implements HasApiTokensContract
{
    use HasApiTokens;

    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserFactory>
     */
    use HasFactory;
    use HasRoles;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'set_password_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    /**
     * @var string
     */
    protected $guard_name = 'sanctum';

    /**
     * @param string $namePermission
     * @param array<string> $permissions
     *
     * @return bool
     */
    public function hasPermissionsViaRoles(string $namePermission, array $permissions): bool
    {
        return in_array($namePermission, $permissions);
    }

    /**
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, User> */
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphToMany<Role, User, MorphPivot>
     * @phpstan-return MorphToMany<Role, User, MorphPivot>
     */
    public function rolesCustom(): MorphToMany
    {
        $relation = $this->morphToMany(
            Role::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            app(PermissionRegistrar::class)->pivotRole
        );

        if (!app(PermissionRegistrar::class)->teams) {
            /** @phpstan-ignore-next-line */
            return $relation;
        }

        $teamField = config('permission.table_names.roles') . '.' . app(PermissionRegistrar::class)->teamsKey;
        /** @phpstan-ignore-next-line */
        return $relation->wherePivot(app(PermissionRegistrar::class)->teamsKey, getPermissionsTeamId())
            ->where(fn ($q) => $q->whereNull($teamField)->orWhere($teamField, getPermissionsTeamId()));
    }

    /**
     * @phpstan-return BelongsToMany<Position, User, PositionsUsers>
     */
    public function positions(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(Position::class, 'positions_users')
            ->using(PositionsUsers::class)
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    /**
     * @param string $password
     *
     * @return void
     */
    public function makePassword($password): void
    {
        $this->password = Hash::make($password);
    }

    /**
     * @param string $namePermission
     *
     * @return bool
     */
    public function hasPermissionRole(string $namePermission): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $this->hasPermissionsViaRoles(
            $namePermission,
            $user->getPermissionsViaRoles()->pluck('name')->toArray()
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

    /**
     * @phpstan-return BelongsToMany<Team, User, TeamsUsers>
     */
    public function teams(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
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

    /**
     * @phpstan-return HasMany<ConfirmationTraining, User>
     */
    public function playerConfirmationsTraining(): HasMany
    {
        /** @phpstan-ignore-next-line */
        return $this->hasMany(ConfirmationTraining::class, 'player_id');
    }

    /**
     * @phpstan-return HasMany<ConfirmationTraining, User>
     */
    public function userConfirmationsTraining(): HasMany
    {
        /** @phpstan-ignore-next-line */
        return $this->hasMany(ConfirmationTraining::class, 'user_id');
    }

    /**
     * @codeCoverageIgnore
     *
     * @phpstan-param Builder<User> $query
     * @phpstan-return Builder<User>
     */
    public function scopeMe(Builder $query): Builder
    {
        return $query->with('positions', 'teams')->where('id', auth()->id());
    }

    /**
     * @phpstan-return HasOne<UserInformation, User>
     */
    public function information(): HasOne
    {
        /** @phpstan-ignore-next-line */
        return $this->hasOne(UserInformation::class);
    }

    /**
     * @codeCoverageIgnore
     *
     * @param  mixed  $args
     */
    public function updateOrNewInformation($args): void
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

    /**
     * @param array<string, mixed> $args
     *
     * @return Builder<User>
     */
    public function list(array $args): Builder
    {
        return $this
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterPosition($args)
            ->filterTeam($args)
            ->filterRoles($args);
    }

    /**
     * @param Builder<User> $query
     * @param array<string, mixed> $args
     *
     * @return void
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        $query->when(
            isset($args['filter']) && isset($args['filter']['search']),
            function (Builder $query) use ($args) {
                $query->where(function (Builder $query) use ($args) {
                    $query
                        ->filterName($args['filter']['search'])
                        ->filterEmail($args['filter']['search'])
                        ->filterUserInformation($args['filter']['search'])
                        ->filterPositionName($args['filter']['search'])
                        ->filterTeamName($args['filter']['search']);
                });
            }
        );
    }

    /**
     * @param Builder<User> $query
     * @param string $search
     *
     * @return void
     */
    public function scopeFilterName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('users.name', 'like', $search);
        });
    }

    /**
     * @param Builder<User> $query
     * @param string $search
     *
     * @return void
     */
    public function scopeFilterEmail(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->orWhere('users.email', 'like', $search);
        });
    }

    /**
     * @param Builder<User> $query
     * @param string $search
     *
     * @return void
     */
    public function scopeFilterUserInformation(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->orWhereHas('information', function ($query) use ($search) {
                // @phpstan-ignore-next-line
                $query->filter($search);
            });
        });
    }

    /**
     * @param Builder<User> $query
     * @param string $search
     *
     * @return void
     */
    public function scopeFilterPositionName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->orWhereHas('positions', function ($query) use ($search) {
                // @phpstan-ignore-next-line
                $query->filterName($search);
            });
        });
    }

    /**
     * @param Builder<User> $query
     * @param string $search
     *
     * @return void
     */
    public function scopeFilterTeamName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->orWhereHas('teams', function ($query) use ($search) {
                // @phpstan-ignore-next-line
                $query->filterName($search);
            });
        });
    }

    /**
     * @param Builder<User> $query
     * @param array<string, mixed> $args
     *
     * @return void
     */
    public function scopeFilterPosition(Builder $query, array $args): void
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['positionsIds']) &&
            !empty($args['filter']['positionsIds']),
            function ($query) use ($args) {
                $query->whereHas('positions', function ($query) use ($args) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($args['filter']['positionsIds']);
                });
            }
        );
    }

    /**
     * @param Builder<User> $query
     * @param array<string, mixed> $args
     *
     * @return void
     */
    public function scopeFilterTeam(Builder $query, array $args): void
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['teamsIds']) &&
            !empty($args['filter']['teamsIds']),
            function ($query) use ($args) {
                $query->whereHas('teams', function ($query) use ($args) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($args['filter']['teamsIds']);
                });
            }
        );
    }

    /**
     * @param Builder<User> $query
     * @param array<string> $ids
     *
     * @return void
     */
    public function scopeFilterIds(Builder $query, array $ids): void
    {
        $query->when(!empty($ids), function ($query) use ($ids) {
            $query->whereIn('users.id', $ids);
        });
    }

    /**
     * @param Builder<User> $query
     * @param array<string, mixed> $args
     *
     * @return void
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('users.id', $args['filter']['ignoreIds']);
        });
    }

    /**
     * @param Builder<User> $query
     * @param array<string, mixed> $args
     *
     * @return void
     */
    public function scopeFilterRoles(Builder $query, array $args): void
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['rolesIds']) &&
            !empty($args['filter']['rolesIds']),
            function ($query) use ($args) {
                $query->whereHas('roles', function ($query) use ($args) {
                    $query->whereIn('id', $args['filter']['rolesIds']);
                });
            }
        );
    }

    /**
     * @return void
     */
    public function saveLastUserChange(): void
    {
        $this->user_id = auth()->user()->id ?? null;
        $this->save();
    }

    /**
     * @param string $tenant
     * @param bool $admin
     *
     * @return void
     */
    public function sendConfirmEmailAndCreatePasswordNotification(string $tenant, $admin = false): void
    {
        $this->set_password_token = Str::random(60);
        $this->save();

        Mail::to($this->email)->send(new ConfirmEmailAndCreatePasswordMail($this, $tenant, $admin));
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return void
     */
    public function sendForgotPasswordNotification(array $args): void
    {
        $user = $this->whereEmail($args['email'])->first();

        if ($user) {
            $user->set_password_token = Str::random(60);
            $user->save();

            Mail::to($user->email)->send(new ForgotPasswordMail($user, tenant('id')));
        }
    }

    /**
     * @param string $typeKey
     * @param string $channel
     *
     * @return bool
     */
    public function canReceiveNotification(string $typeKey, string $channel = 'system'): bool
    {
        $channelColumn = match ($channel) {
            'email' => 'via_email',
            'system' => 'via_system',
            default => null,
        };

        if (!$channelColumn) {
            return false;
        }

        return $this->notificationSettings()
            ->whereHas('notificationType', function ($query) use ($typeKey) {
                $query
                    ->where('key', $typeKey)
                    ->where('is_active', true);
            })
            ->where($channelColumn, true)
            ->exists();
    }

    /**
     * @phpstan-return HasMany<NotificationSetting, User>
     */
    public function notificationSettings(): HasMany
    {
        /** @phpstan-ignore-next-line */
        return $this->hasMany(NotificationSetting::class);
    }
}
