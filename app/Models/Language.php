<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    /**
     * @param  array<string, mixed>  $args
     * @return Builder<Language>
     */
    public function list(array $args): Builder
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args);
    }

    /**
     * @param  Builder<Language>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        $query->when(
            is_array($filter) &&
            array_key_exists('search', $filter) &&
            is_string($filter['search']),
            function (Builder $query) use ($filter): void {
                /** @psalm-var array{search: string} $filter */
                $search = $filter['search'];
                $query
                    ->filterName($search)
                    ->orWhere(function (Builder $query) use ($search): void {
                        $query->filterSlug($search);
                    });
            }
        );
    }

    /**
     * @param  Builder<Language>  $query
     */
    public function scopeFilterName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function (Builder $query) use ($search): void {
            $query->where('languages.name', 'like', $search);
        });
    }

    /**
     * @param  Builder<Language>  $query
     */
    public function scopeFilterSlug(Builder $query, string $search): void
    {
        $query->when(!empty($search), function (Builder $query) use ($search): void {
            $query->where('languages.slug', 'like', $search);
        });
    }

    /**
     * @param  Builder<Language>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        /** @var array<string, mixed>|null $filter */
        $filter = $args['filter'] ?? null;

        $query->when(
            is_array($filter) &&
            array_key_exists('ignoreIds', $filter) &&
            is_array($filter['ignoreIds']),
            function (Builder $query) use ($filter): void {
                /** @psalm-var array{ignoreIds: array<int>} $filter */
                $ignoreIds = $filter['ignoreIds'];
                $query->whereNotIn('languages.id', $ignoreIds);
            }
        );
    }
}
