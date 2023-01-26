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
        'presence'
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
        return $query->where('status', $status);
    }

    public function scopePresence($query, $presence)
    {
        return $query->where('presence', $presence);
    }

    public function scopePlayer($query, $player)
    {
        return $query->where('player_id', $player);
    }

    public function scopeTeam($query, $team)
    {
        return $query->where('team_id', $team);
    }

    public function scopeTraining($query, $training)
    {
        return $query->where('training_id', $training);
    }

    public function scopeUser($query, $user)
    {
        return $query->where('user_id', $user);
    }

}
