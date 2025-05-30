<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    /**
     * @param array<string, mixed> $args
     * 
     * @return Builder<Language>
     */
    public function list(array $args): Builder
    {
        return $this
            ->filterSearch($args)
            ->filterIgnores($args);
    }

    /**
     * @param Builder<Language> $query
     * @param array<string, mixed> $args
     * 
     * @return void
     */
    public function scopeFilterSearch(Builder $query, array $args): void
    {
        $query->when(isset($args['filter']) &&
            isset($args['filter']['search']), function ($query) use ($args) {
                $query
                    ->filterName($args['filter']['search'])
                    ->orWhere(function ($query) use ($args) {
                        $query
                            ->filterSlug($args['filter']['search']);
                    });
            });
    }

    /**
     * @param Builder<Language> $query
     * @param string $search
     * 
     * @return void
     */
    public function scopeFilterName(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('languages.name', 'like', $search);
        });
    }

    /**
     * @param Builder<Language> $query
     * @param string $search
     * 
     * @return void
     */
    public function scopeFilterSlug(Builder $query, string $search): void
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where('languages.slug', 'like', $search);
        });
    }

    /**
     * @param Builder<Language> $query
     * @param array<string, mixed> $args
     * 
     * @return void
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        $query->when(isset($args['filter']) && isset($args['filter']['ignoreIds']), function ($query) use ($args) {
            $query->whereNotIn('languages.id', $args['filter']['ignoreIds']);
        });
    }
}
