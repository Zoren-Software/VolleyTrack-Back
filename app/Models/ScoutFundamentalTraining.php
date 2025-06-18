<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoutFundamentalTraining extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ScoutFundamentalTrainingFactory>
     */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'scout_fundamentals_training';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'player_id',
        'training_id',
        'position_id',
    ];

    /**
     * @return BelongsTo<User, ScoutFundamentalTraining>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, ScoutFundamentalTraining>
     */
    public function player(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Training, ScoutFundamentalTraining>
     */
    public function training(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(Training::class);
    }

    /**
     * @return BelongsTo<Position, ScoutFundamentalTraining>
     */
    public function position(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(Position::class);
    }

    /**
     * @param  array<string, mixed>  $args
     * @return Builder<ScoutFundamentalTraining>
     */
    public function list(array $args)
    {
        return $this
            ->filterIgnores($args)
            ->filterUser($args)
            ->filterPlayer($args)
            ->filterTraining($args)
            ->filterPosition($args);
    }

    /**
     * @param  Builder<ScoutFundamentalTraining>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterUser(Builder $query, array $args): void
    {
        /** @var array{userId?: int} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['userId'])) {
            $query->where('user_id', $filter['userId']);
        }
    }

    /**
     * @param  Builder<ScoutFundamentalTraining>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterPlayer(Builder $query, array $args): void
    {
        /** @var array{playerId?: int} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['playerId'])) {
            $query->where('player_id', $filter['playerId']);
        }
    }

    /**
     * @param  Builder<ScoutFundamentalTraining>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterTraining(Builder $query, array $args): void
    {
        /** @var array{trainingId?: int} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['trainingId'])) {
            $query->where('training_id', $filter['trainingId']);
        }
    }

    /**
     * @param  Builder<ScoutFundamentalTraining>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterPosition(Builder $query, array $args): void
    {
        /** @var array{positionId?: int} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['positionId'])) {
            $query->where('position_id', $filter['positionId']);
        }
    }

    /**
     * @param  Builder<ScoutFundamentalTraining>  $query
     * @param  array<string, mixed>  $args
     */
    public function scopeFilterIgnores(Builder $query, array $args): void
    {
        /** @var array{ignores?: array<array-key, int>} $filter */
        $filter = is_array($args['filter'] ?? null) ? $args['filter'] : [];

        if (isset($filter['ignores'])) {
            $query->whereNotIn('id', $filter['ignores']);
        }
    }
}
