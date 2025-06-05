<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfirmationTraining extends Model
{
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'player_id',
        'training_id',
        'team_id',
        'status',
        'presence',
    ];

    /**
     * @return BelongsTo<User, ConfirmationTraining>
     */
    public function user(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, ConfirmationTraining>
     */
    public function player(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Training, ConfirmationTraining>
     */
    public function training(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(Training::class);
    }

    /**
     * @return BelongsTo<Team, ConfirmationTraining>
     */
    public function team(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(Team::class);
    }

    /**
     * @param  Builder<ConfirmationTraining>  $query
     * @return Builder<ConfirmationTraining>
     */
    public function scopeStatus(Builder $query, ?string $status = null): Builder
    {
        if ($status === null) {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * @param  Builder<ConfirmationTraining>  $query
     * @return Builder<ConfirmationTraining>
     */
    public function scopePresence(Builder $query, ?string $presenceId = null): Builder
    {
        if ($presenceId === null) {
            return $query;
        }

        return $query->where('presence', $presenceId);
    }

    /**
     * @param  Builder<ConfirmationTraining>  $query
     * @return Builder<ConfirmationTraining>
     */
    public function scopePlayer(Builder $query, ?string $playerId = null): Builder
    {
        if ($playerId === null) {
            return $query;
        }

        return $query->where('player_id', $playerId);
    }

    /**
     * @param  Builder<ConfirmationTraining>  $query
     * @return Builder<ConfirmationTraining>
     */
    public function scopeTeam(Builder $query, ?string $teamId = null): Builder
    {
        if ($teamId === null) {
            return $query;
        }

        return $query->where('team_id', $teamId);
    }

    /**
     * @param  Builder<ConfirmationTraining>  $query
     * @return Builder<ConfirmationTraining>
     */
    public function scopeTraining(Builder $query, ?string $trainingId = null): Builder
    {
        if ($trainingId === null) {
            return $query;
        }

        return $query->where('training_id', $trainingId);
    }

    /**
     * @param  Builder<ConfirmationTraining>  $query
     * @return Builder<ConfirmationTraining>
     */
    public function scopeUser(Builder $query, ?string $userId = null): Builder
    {
        if ($userId === null) {
            return $query;
        }

        return $query->where('user_id', $userId);
    }

    /**
     * @param  array<string, mixed>  $args
     * @return Builder<ConfirmationTraining>
     */
    public function list(array $args): Builder
    {
        return $this->status($args['status'] ?? null)
            ->presence($args['presence'] ?? null)
            ->player($args['player_id'] ?? null)
            ->team($args['team_id'] ?? null)
            ->training($args['training_id'] ?? null)
            ->user($args['user_id'] ?? null)
            ->orderBy('created_at', 'desc');
    }
}
