<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationSetting extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

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

    public function list(array $args)
    {
        return auth()
            ->user()
            ->notificationSettings()
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

    public function scopeFilter(Builder $query, array $args)
    {
        return
            $query->filterIsActive($args)
                ->filterViaEmail($args)
                ->filterViaSystem($args)
                ->orderBy('created_at', 'desc');
    }

    /**
     * @param  Builder  $query
     * @param  array $args
     */
    public function scopeFilterIsActive(Builder $query, array $args)
    {
        return 
            $query->when(isset($args['filter']) && isset($args['filter']['is_active']), function ($query) use ($args) {
                $query->where('is_active', $args['filter']['is_active']);
            });
    }

    /**
     * @param  Builder  $query
     * @param  array $args
     */
    public function scopeFilterViaEmail(Builder $query, array $args)
    {
        return 
            $query->when(isset($args['filter']) && isset($args['filter']['via_email']), function ($query) use ($args) {
                $query->where('via_email', $args['filter']['via_email']);
            });
    }
    /**
     * @param  Builder  $query
     * @param  array $args
     */
    public function scopeFilterViaSystem(Builder $query, array $args)
    {
        return 
            $query->when(isset($args['filter']) && isset($args['filter']['via_system']), function ($query) use ($args) {
                $query->where('via_system', $args['filter']['via_system']);
            });
    }

    /**
     * @return [type]
     */
    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class, 'notification_type_id');
    }

    /**
     * @return [type]
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
