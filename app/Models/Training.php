<?php

namespace App\Models;

use App\Notifications\Training\NotificationCancelTrainingNotification;
use App\Notifications\Training\NotificationConfirmationTrainingNotification;
use App\Notifications\Training\TrainingNotification;
use App\Rules\RelationshipSpecificFundamental;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Training extends Model
{
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

    private $format = 'd/m/Y';

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

    public function specificFundamentals()
    {
        return $this->belongsToMany(SpecificFundamental::class, 'specific_fundamentals_trainings')
            ->using(SpecificFundamentalsTrainings::class)
            ->as('specific_fundamentals')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function confirmationsTraining()
    {
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

    public static function rules($fundamentalIds)
    {
        return [
            'name' => [
                'required',
                'min:3',
            ],
            'teamId' => [
                'required',
            ],
            'specificFundamentalId' => [
                new RelationshipSpecificFundamental($fundamentalIds),
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
     *
     * @param  null  $daysNotification
     * @return void
     */
    public function sendNotificationTechnicians(int $daysNotification = null)
    {
        $this->team->technicians()->each(function ($technician) use ($daysNotification) {
            if (
                $this->rangeDateNotification(
                    $this->date_start->format($this->format),
                    now()->format($this->format),
                    now()->addDays($daysNotification)->format($this->format)
                )
            ) {
                $technician->notify(new NotificationConfirmationTrainingNotification($this, null));
            }
        });
    }

    public function rangeDateNotification(string $startDate, string $dateToday, string $dateLimit)
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
    public function confirmationsPlayers(int $trainingId = null, int $daysNotification = null)
    {
        $daysNotification = $daysNotification ?? TrainingConfig::first()->days_notification;
        $this->team->players()->each(function ($player) use ($trainingId, $daysNotification) {
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

            if (
                $this->rangeDateNotification(
                    $this->date_start->format($this->format),
                    now()->format($this->format),
                    now()->addDays($daysNotification)->format($this->format)
                )
            ) {
                $player->notify(new TrainingNotification($this, $confirmationTraining));
            }
        });

        $this->sendNotificationTechnicians($daysNotification);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function sendNotificationPlayersTrainingCancelled()
    {
        $this->team->players()->each(function ($player) {
            $player->notify(new NotificationCancelTrainingNotification($this, null));
        });
    }

    /**
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function metrics()
    {
        $confirmed = $this->confirmationsTraining()->status('confirmed')->count() ?? 0;
        $pending = $this->confirmationsTraining()->status('pending')->count() ?? 0;
        $rejected = $this->confirmationsTraining()->status('rejected')->count() ?? 0;
        $total = $confirmed + $pending + $rejected;

        return [
            'confirmed' => $confirmed,
            'pending' => $pending,
            'rejected' => $rejected,
            'total' => $total,
            'confirmedPercentage' => $confirmed > 0 ? ($confirmed / ($total) * 100) : 0,
            'pendingPercentage' => $pending > 0 ? ($pending / ($total) * 100) : 0,
            'rejectedPercentage' => $rejected > 0 ? ($rejected / ($total) * 100) : 0,
        ];
    }

    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterTeam($args)
            ->filterUser($args);
        //->filterDateStart($args);
    }

    public function scopeFilterSearch(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['search']), function ($query) use ($args) {
            $query
                ->filterName($args['filter']['search'])
                ->orWhere(function ($query) use ($args) {
                    $query
                        ->filterUserName($args['filter']['search'])
                        ->filterTeamName($args['filter']['search']);
                });
        });
    }

    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->where('trainings.name', 'like', $search);
        });
    }

    public function scopeFilterTeamName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->orWhereHas('team', function ($query) use ($search) {
                $query->filterName($search);
            });
        });
    }

    public function scopeFilterUserName(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->orWhereHas('user', function ($query) use ($search) {
                $query->filterName($search);
            });
        });
    }

    public function scopeFilterTeam(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['teamsIds']) &&
            !empty($args['filter']['teamsIds']),
            function ($query) use ($args) {
                $query->whereHas('team', function ($query) use ($args) {
                    $query->filterIds($args['filter']['teamsIds']);
                });
            }
        )->when(
            isset($args['filter']) &&
            isset($args['filter']['playersIds']) &&
            !empty($args['filter']['playersIds']),
            function ($query) use ($args) {
                $query->whereHas('team', function ($query) use ($args) {
                    $query->filterByTeamPlayer($args);
                });
            }
        );

    }

    public function scopeFilterUser(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['usersIds']) &&
            !empty($args['filter']['usersIds']),
            function ($query) use ($args) {
                $query->whereHas('user', function ($query) use ($args) {
                    $query->filterIds($args['filter']['usersIds']);
                });
            }
        );
    }
}
