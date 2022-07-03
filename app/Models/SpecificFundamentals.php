<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecificFundamentals extends Model
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
        return $this->belongsToMany(Fundamentals::class, 'fundamentals_specific_fundamentals', 'specific_fundamental_id', 'fundamental_id')->withTimestamps()->withPivot('created_at', 'updated_at');
    }
}
