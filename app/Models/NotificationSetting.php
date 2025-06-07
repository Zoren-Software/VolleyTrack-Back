<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationSetting extends Model
{
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'notification_type_id',
        'via_email',
        'via_system',
        'is_active',
    ];

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
     * @param  Builder<NotificationSetting>  $query
     * @param  array<string, mixed>  $args
     * @return Builder<NotificationSetting>
     */
    public function scopeList(Builder $query, array $args)
    {
        return $query->where('user_id', auth()->id())
            ->whereHas('notificationType', function ($query) {
                // NOTE - Para não mostrar os tipos de notificação que não são editáveis
                // ou que não são mostrados na lista de configurações
                $query->where('show_list', true);
            })
            ->select([
                'id',
                'user_id',
                'notification_type_id',
                'via_email',
                'via_system',
                'is_active',
                'created_at',
                'updated_at',
            ])
            ->with([
                'notificationType:id,key,description,allow_email,allow_system,is_active,created_at,updated_at',
            ])
            ->filter($args);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<\App\Models\NotificationSetting>  $query
     * @param  array<string, mixed>  $args
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\NotificationSetting>
     */
    public function scopeFilter(Builder $query, array $args): Builder
    {
        return $query->filterIsActive($args)
            ->filterViaEmail($args)
            ->filterViaSystem($args)
            ->orderBy('created_at', 'desc');
    }

    /**
     * @param  Builder<NotificationSetting>  $query
     * @param  array<string, mixed>  $args
     * @return Builder<NotificationSetting>
     */
    public function scopeFilterIsActive(Builder $query, array $args): Builder
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        return $query->when(
            is_array($filter) && array_key_exists('is_active', $filter),
            function (Builder $query) use ($filter): Builder {
                assert(is_array($filter) && array_key_exists('is_active', $filter));
                /** @var bool|int|string $isActive */
                $isActive = $filter['is_active'];

                return $query->where('is_active', $isActive);
            }
        );
    }

    /**
     * @param  Builder<NotificationSetting>  $query
     * @param  array<string, mixed>  $args
     * @return Builder<NotificationSetting>
     */
    public function scopeFilterViaEmail(Builder $query, array $args): Builder
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        return $query->when(
            is_array($filter) && array_key_exists('via_email', $filter),
            function (Builder $query) use ($filter): Builder {
                assert(is_array($filter) && array_key_exists('via_email', $filter));
                /** @var bool|int|string $viaEmail */
                $viaEmail = $filter['via_email'];

                return $query->where('via_email', $viaEmail);
            }
        );
    }

    /**
     * @param  Builder<NotificationSetting>  $query
     * @param  array<string, mixed>  $args
     * @return Builder<NotificationSetting>
     */
    public function scopeFilterViaSystem(Builder $query, array $args): Builder
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        return $query->when(
            is_array($filter) && array_key_exists('via_system', $filter),
            function (Builder $query) use ($filter): Builder {
                assert(is_array($filter) && array_key_exists('via_system', $filter));
                /** @var bool|int|string $viaSystem */
                $viaSystem = $filter['via_system'];

                return $query->where('via_system', $viaSystem);
            }
        );
    }

    /**
     * @return BelongsTo<NotificationType, NotificationSetting>
     */
    public function notificationType(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(NotificationType::class, 'notification_type_id');
    }

    /**
     * @return BelongsTo<User, NotificationSetting>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class, 'user_id');
    }
}
