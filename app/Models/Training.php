<?php

namespace App\Models;

use App\Notifications\Training\NotificationConfirmationTrainingNotification;
use App\Notifications\Training\TrainingNotification;
use App\Rules\RelationshipSpecificFundamental;
use Carbon\Carbon;
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

    protected $dates = [
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

    public function specificFundamentals()
    {
        return $this->belongsToMany(SpecificFundamental::class, 'specific_fundamentals_trainings')
            ->using(SpecificFundamentalsTrainings::class)
            ->as('specific_fundamentals')
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

    public static function rules($fundamentalIds)
    {
        return [
            'name' => [
                'required',
                'min:3',
            ],
            'userId' => [
                'required',
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

    public function sendNotificationPlayers()
    {
        $this->team->players()->each(function ($player) {
            $format = 'd/m/Y';
            if (
                $this->rangeDateNotification(
                    $this->date_start->format($format),
                    now()->format($format),
                    now()->addDays(TrainingConfig::first()->days_notification)->format($format)
                )
            ) {
                $player->notify(new TrainingNotification($this));
            }
        });

        $this->team->technicians()->each(function ($technician) {
            $format = 'd/m/Y';
            if (
                $this->rangeDateNotification(
                    $this->date_start->format($format),
                    now()->format($format),
                    now()->addDays(TrainingConfig::first()->days_notification)->format($format)
                )
            ) {
                $technician->notify(new NotificationConfirmationTrainingNotification($this));
            }
        });
    }

    public function rangeDateNotification(string $startDate, string $dateToday, string $dateLimit)
    {
        $startDate = Carbon::createFromFormat('d/m/Y', $startDate);
        $dateToday = Carbon::createFromFormat('d/m/Y', $dateToday);
        $dateLimit = Carbon::createFromFormat('d/m/Y', $dateLimit);

        return $startDate >= $dateToday && $startDate <= $dateLimit;
    }
}
