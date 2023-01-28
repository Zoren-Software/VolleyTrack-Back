<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfirmationTraining extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'player_id',
        'training_id',
        'team_id',
        'status',
        'presence',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function player()
    {
        return $this->belongsTo(User::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeStatus($query, $status)
    {
        if ($status === null) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function scopePresence($query, $presenceId)
    {
        if ($presenceId === null) {
            return $query;
        }

        return $query->where('presence', $presenceId);
    }

    public function scopePlayer($query, $playerId)
    {
        if ($playerId === null) {
            return $query;
        }

        return $query->where('player_id', $playerId);
    }

    public function scopeTeam($query, $teamId)
    {
        if ($teamId === null) {
            return $query;
        }

        return $query->where('team_id', $teamId);
    }

    public function scopeTraining($query, $trainingId)
    {
        if ($trainingId === null) {
            return $query;
        }

        return $query->where('training_id', $trainingId);
    }

    public function scopeUser($query, $userId)
    {
        if ($userId === null) {
            return $query;
        }

        return $query->where('user_id', $userId);
    }

    public function list(array $args)
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
