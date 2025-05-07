<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamLevel extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
