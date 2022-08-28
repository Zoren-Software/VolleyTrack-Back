<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'positions_users')
        ->using(PositionsUsers::class)
        ->withTimestamps()
        ->withPivot('created_at', 'updated_at');
    }

    /**
     * @codeCoverageIgnore
     *
     * @return Position
     */
    public function deletePosition(int $id): Position
    {
        $position = $this->findOrFail($id);
        $position->delete();

        return $position;
    }
}
