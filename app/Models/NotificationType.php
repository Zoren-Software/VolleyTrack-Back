<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class NotificationType extends Model
{
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'description',
        'allow_email',
        'allow_system',
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
     * @param  array<string, mixed>  $args
     * @return Builder<NotificationType>
     */
    public function list(array $args): Builder
    {
        return $this
            ->filterSearch($args);
    }

    /**
     * @param  Builder<NotificationType>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        /** @var string|null $search */
        $search = (is_array($filter) && isset($filter['search']) && is_string($filter['search']))
            ? $filter['search']
            : null;

        $query->when($search !== null, function (Builder $query) use ($search): void {
            // @phpstan-ignore-next-line
            $query->filterName($search);
        });
    }
}
