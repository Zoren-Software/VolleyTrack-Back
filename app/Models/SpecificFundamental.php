<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Fundamental[] $fundamentals
 */
class SpecificFundamental extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SpecificFundamentalFactory>
     */
    use HasFactory;

    use LogsActivity;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo<User, SpecificFundamental>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Fundamental, SpecificFundamental>
     */
    public function fundamentals(): BelongsToMany
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsToMany(Fundamental::class)
            ->using(FundamentalsSpecificFundamentals::class)
            ->as('fundamentals')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    /**
     * @codeCoverageIgnore
     */
    public function deleteSpecificFundamental(int $id): SpecificFundamental
    {
        $specificFundamental = $this->findOrFail($id);
        $specificFundamental->delete();

        return $specificFundamental;
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
     * @param  array<string, mixed>  $args
     * @return Builder<SpecificFundamental>
     */
    public function list(array $args)
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args)
            ->filterUser($args)
            ->filterFundamentals($args);
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @param  array<string, mixed>  $args
     * @return void
     */
    public function scopeFilterSearch(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) &&
            isset($args['filter']['search']), function ($query) use ($args) {
                $query
                    ->filterName($args['filter']['search'])
                    ->orWhere(function ($query) use ($args) {
                        $query
                            ->filterUserName($args['filter']['search']);
                    });
            });
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @return void
     */
    public function scopeFilterName(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('specific_fundamentals.name', 'like', $search);
        });
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
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
     * @param  Builder<SpecificFundamental>  $query
     * @param  array<string, mixed>  $args
     * @return void
     */
    public function scopeFilterUser(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['usersIds']) &&
            !empty($args['filter']['usersIds']),
            function ($query) use ($args) {
                $query->whereHas('user', function ($query) use ($args) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($args['filter']['usersIds']);
                });
            }
        );
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @param  array<string, mixed>  $args
     * @return void
     */
    public function scopeFilterIgnores(Builder $query, array $args)
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('specific_fundamentals.id', $args['filter']['ignoreIds']);
        });
    }

    /**
     * @param  Builder<SpecificFundamental>  $query
     * @param  array<string, mixed>  $args
     * @return void
     */
    public function scopeFilterFundamentals(Builder $query, array $args)
    {
        $query->when(
            isset($args['filter']) &&
            isset($args['filter']['fundamentalsIds']) &&
            !empty($args['filter']['fundamentalsIds']),
            function ($query) use ($args) {
                $query->whereHas('fundamentals', function ($query) use ($args) {
                    // @phpstan-ignore-next-line
                    $query->filterIds($args['filter']['fundamentalsIds']);
                });
            }
        );
    }
}
