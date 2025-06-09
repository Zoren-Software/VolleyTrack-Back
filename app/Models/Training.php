<?php

namespace App\Models;

use App\Mail\Training\CancellationTrainingMail;
use App\Mail\Training\ConfirmationTrainingMail;
use App\Mail\Training\TrainingMail;
use App\Notifications\Training\CancelTrainingNotification;
use App\Notifications\Training\ConfirmationTrainingNotification;
use App\Notifications\Training\TrainingNotification;
use App\Rules\RelationshipSpecificFundamental;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property \App\Models\Team $team
 */
class Training extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\TrainingFactory>
     */
    use HasFactory;

    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'user_id',
        'name',
        'description',
        'status',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'date_start' => 'datetime:Y-m-d H:i:s',
        'date_end' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @var string
     */
    private $format = 'd/m/Y';

    /**
     * @phpstan-return BelongsTo<User, Training>
     *
     * @return BelongsTo<User, Training>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @phpstan-return BelongsTo<Team, Training>
     *
     * @return BelongsTo<Team, Training>
     */
    public function team()
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(Team::class);
    }

    /**
     * @phpstan-return BelongsToMany<Fundamental, Training, FundamentalsTrainings>
     *
     * @return BelongsToMany<Fundamental, Training, FundamentalsTrainings>
     */
    public function fundamentals(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(Fundamental::class, 'fundamentals_trainings')
            ->using(FundamentalsTrainings::class)
            ->as('fundamentals')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    /**
     * @phpstan-return BelongsToMany<SpecificFundamental, Training, SpecificFundamentalsTrainings>
     *
     * @return BelongsToMany<SpecificFundamental, Training, SpecificFundamentalsTrainings>
     */
    public function specificFundamentals(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(SpecificFundamental::class, 'specific_fundamentals_trainings')
            ->using(SpecificFundamentalsTrainings::class)
            ->as('specific_fundamentals')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    /**
     * @phpstan-return HasMany<ConfirmationTraining, Training>
     *
     * @return HasMany<ConfirmationTraining, Training>
     */
    public function confirmationsTraining(): HasMany
    {
        /** @phpstan-ignore-next-line */
        return $this->hasMany(ConfirmationTraining::class);
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

    /**
     * @param  array<string>  $fundamentalIds
     * @return array<string, list<Rule|string>>
     *
     * @phpstan-ignore-next-line
     */
    public static function rules(array $fundamentalIds): array
    {
        // Convertendo para array<int>
        $numericFundamentalIds = array_map('intval', $fundamentalIds);

        /** @phpstan-ignore-next-line */
        return [
            'name' => [
                'required',
                'min:3',
            ],
            'teamId' => [
                'required',
            ],
            'specificFundamentalId' => [
                new RelationshipSpecificFundamental($numericFundamentalIds),
            ],
            'dateStart' => [
                'required',
                'date',
                'date_format:Y-m-d H:i:s',
                'before:dateEnd',
            ],
            'dateEnd' => [
                'required',
                'date',
                'date_format:Y-m-d H:i:s',
                'after:dateStart',
            ],
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function sendNotificationTechnicians(?int $daysNotification = null): void
    {
        $this->team->technicians()->each(function (User $technician) use ($daysNotification) {
            if (
                $technician->email_verified_at &&
                $this->rangeDateNotification(
                    $this->date_start->format($this->format),
                    now()->format($this->format),
                    now()->addDays($daysNotification ?? 0)->format($this->format)
                )
            ) {
                if ($technician->canReceiveNotification('training_created', 'system')) {
                    $technician->notify(new ConfirmationTrainingNotification($this, null));
                }
        
                if ($technician->canReceiveNotification('training_created', 'email')) {
                    Mail::to($technician->email)
                        ->send(new ConfirmationTrainingMail($this, $technician));
                }
            }
        });
    }

    /**
     * @codeCoverageIgnore
     *
     * @param  null  $daysNotification
     */
    public function sendNotificationPlayers(?int $daysNotification = null): void
    {
        $this->team->players()->each(function (User $player) use ($daysNotification) {
            if (
                $player->email_verified_at &&
                $this->rangeDateNotification(
                    $this->date_start->format($this->format),
                    now()->format($this->format),
                    now()->addDays($daysNotification ?? 0)->format($this->format)
                )
            ) {
                if ($player->canReceiveNotification('training_created', 'system')) {
                    $player->notify(new TrainingNotification($this, null));
                }

                if ($player->canReceiveNotification('training_created', 'email')) {
                    Mail::to($player->email)
                        ->send(new TrainingMail($this, $player));
                }
            }
        });
    }

    public function rangeDateNotification(string $startDate, string $dateToday, string $dateLimit): bool
    {
        $startDate = Carbon::createFromFormat($this->format, $startDate);
        $dateToday = Carbon::createFromFormat($this->format, $dateToday);
        $dateLimit = Carbon::createFromFormat($this->format, $dateLimit);

        return $startDate >= $dateToday && $startDate <= $dateLimit;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function confirmationsPlayers(?int $trainingId = null, ?int $daysNotification = null)
    {
        $daysNotification = $daysNotification ?? TrainingConfig::first()->days_notification ?? 0;

        $this->team->players()->each(function (User $player) use ($trainingId, $daysNotification) {
            $confirmationTraining = $this->confirmationsTraining()
                ->where('training_id', $trainingId)
                ->where('player_id', $player->id)
                ->first();

            if ($trainingId === null || $confirmationTraining === null) {
                $confirmationTraining = $this->confirmationsTraining()->create([
                    'user_id' => auth()->user()->id ?? null,
                    'player_id' => $player->id,
                    'team_id' => $this->team_id,
                    'training_id' => $this->id,
                    'status' => 'pending',
                    'presence' => false,
                ]);
            } else {
                $confirmationTraining->update([
                    'user_id' => auth()->user()->id ?? null,
                    'player_id' => $player->id,
                    'team_id' => $this->team_id,
                    'training_id' => $this->id,
                    'status' => 'pending',
                    'presence' => false,
                ]);
            }

            /** @var \App\Models\ConfirmationTraining $confirmationTraining */
            if (
                $player->email_verified_at &&
                $this->rangeDateNotification(
                    $this->date_start->format($this->format),
                    now()->format($this->format),
                    now()->addDays($daysNotification)->format($this->format)
                )
            ) {
                if ($player->canReceiveNotification('training_created', 'system')) {
                    $player->notify(new TrainingNotification($this, $confirmationTraining));
                }

                if ($player->canReceiveNotification('training_created', 'email')) {
                    Mail::to($player->email)
                        ->send(new TrainingMail($this, $player));
                }
            }
        });

        $this->sendNotificationTechnicians($daysNotification);
    }

    public function deleteConfirmationsPlayersOld(int $teamId): void
    {
        $this->confirmationsTraining()
            ->where('team_id', $teamId)
            ->where('training_id', $this->id)
            ->delete();
    }

    /**
     * @codeCoverageIgnore
     */
    public function sendNotificationPlayersTrainingCancelled(): void
    {
        $this->team->players()->each(function (User $player) {
            if (
                $player->email_verified_at &&
                $player->canReceiveNotification('training_cancelled', 'system')
            ) {
                $player->notify(new CancelTrainingNotification($this));
            }
        });
    }

    public function sendEmailPlayersTrainingCancelled(): void
    {
        $this->team->players()->each(function (User $player) {
            if (
                $player->email_verified_at &&
                $player->canReceiveNotification('training_cancelled', 'email')
            ) {
                Mail::to($player->email)
                    ->send(new CancellationTrainingMail($this, $player));
            }
        });
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array<string, int|float>
     */
    public function metrics(): array
    {
        $confirmed = $this->confirmationsTraining()->where('status', 'confirmed')->count();
        $pending = $this->confirmationsTraining()->where('status', 'pending')->count();
        $rejected = $this->confirmationsTraining()->where('status', 'rejected')->count();
        $presence = $this->confirmationsTraining()->where('presence', true)->count();
        $absence = $this->confirmationsTraining()->where('presence', false)->count();

        $total = $confirmed + $pending + $rejected;

        return [
            'confirmed' => $confirmed,
            'pending' => $pending,
            'rejected' => $rejected,
            'total' => $total,
            'confirmedPercentage' => $confirmed > 0 ? ($confirmed / ($total) * 100) : 0,
            'pendingPercentage' => $pending > 0 ? ($pending / ($total) * 100) : 0,
            'rejectedPercentage' => $rejected > 0 ? ($rejected / ($total) * 100) : 0,
            'presence' => $presence,
            'absence' => $absence,
            'presencePercentage' => $presence > 0 ? ($presence / ($total) * 100) : 0,
            'absencePercentage' => $absence > 0 ? ($absence / ($total) * 100) : 0,
        ];
    }

    /**
     * @param  Builder<Training>  $query
     * @param  array<string, mixed>  $args
     * @return Builder<Training>
     */
    public function scopeList(Builder $query, array $args)
    {
        return $query
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterTeam($args)
            ->filterUser($args)
            ->filterDate($args);
    }

    /**
     * @param  Builder<Training>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        if (
            isset($args['filter']) &&
            is_array($args['filter']) &&
            isset($args['filter']['search']) &&
            is_string($args['filter']['search'])
        ) {
            $filter = $args['filter'];

            $query->where(function (Builder $query) use ($filter) {
                $query
                    ->filterName($filter['search'])
                    ->orWhere(function (Builder $query) use ($filter) {
                        $query
                            ->filterUserName($filter['search'])
                            ->filterTeamName($filter['search']);
                    });
            });
        }
    }

    /**
     * @param  Builder<Training>  $query
     * @return void
     */
    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('trainings.name', 'like', $search);
        });
    }

    /**
     * @param  Builder<Training>  $query
     * @return void
     */
    public function scopeFilterTeamName(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->orWhereHas('team', function ($query) use ($search) {
                // @phpstan-ignore-next-line
                $query->filterName($search);
            });
        });
    }

    /**
     * @param  Builder<Training>  $query
     * @return void
     */
    public function scopeFilterUserName(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->orWhereHas('user', function ($query) use ($search) {
                // @phpstan-ignore-next-line
                $query->filterName($search);
            });
        });
    }

    /**
     * @param  Builder<Training>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterTeam(Builder $query, array $args): void
    {
        if (
            isset($args['filter']) &&
            is_array($args['filter'])
        ) {
            $filter = $args['filter'];

            if (
                isset($filter['teamsIds']) &&
                is_array($filter['teamsIds']) &&
                !empty($filter['teamsIds'])
            ) {
                $query->whereHas('team', function ($query) use ($filter) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($filter['teamsIds']);
                });
            }

            if (
                isset($filter['playersIds']) &&
                is_array($filter['playersIds']) &&
                !empty($filter['playersIds'])
            ) {
                $query->whereHas('team', function ($query) use ($args) {
                    // @phpstan-ignore-next-line
                    $query->filterByTeamPlayer($args);
                });
            }
        }
    }

    /**
     * @param  Builder<Training>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterUser(Builder $query, array $args): void
    {
        if (
            isset($args['filter']) &&
            is_array($args['filter'])
        ) {
            /** @var array<string, mixed> $filter */
            $filter = $args['filter'];

            if (
                isset($filter['usersIds']) &&
                is_array($filter['usersIds']) &&
                !empty($filter['usersIds'])
            ) {
                $query->whereHas('user', function ($query) use ($filter) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($filter['usersIds']);
                });
            }
        }
    }

    /**
     * @param  Builder<Training>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        if (
            isset($args['filter']) &&
            is_array($args['filter']) &&
            isset($args['filter']['ignoreIds']) &&
            is_array($args['filter']['ignoreIds'])
        ) {
            /** @var array<string, mixed> $filter */
            $filter = $args['filter'];

            $query->whereNotIn('trainings.id', $filter['ignoreIds']);
        }
    }

    /**
     * @param  Builder<Training>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterDate(Builder $query, array $args): void
    {
        if (
            isset($args['filter']) &&
            is_array($args['filter']) &&
            isset($args['filter']['dateStart']) &&
            is_string($args['filter']['dateStart']) &&
            !empty($args['filter']['dateStart'])
        ) {
            /** @var array{dateStart: string} $filter */
            $filter = $args['filter'];
            $query->whereDate('trainings.date_start', '>=', $filter['dateStart']);
        }

        if (
            isset($args['filter']) &&
            is_array($args['filter']) &&
            isset($args['filter']['dateEnd']) &&
            is_string($args['filter']['dateEnd']) &&
            !empty($args['filter']['dateEnd'])
        ) {
            /** @var array{dateEnd: string} $filter */
            $filter = $args['filter'];
            $query->whereDate('trainings.date_end', '<=', $filter['dateEnd']);
        }
    }
}
