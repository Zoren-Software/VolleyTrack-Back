<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecificFundamental extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fundamentals()
    {
        return $this->belongsToMany(Fundamental::class)
            ->using(FundamentalsSpecificFundamentals::class)
            ->as('fundamentals')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }
}
