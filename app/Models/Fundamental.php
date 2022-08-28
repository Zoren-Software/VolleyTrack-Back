<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fundamental extends Model
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

    public function specificFundamental()
    {
        return $this->hasMany(SpecificFundamental::class);
    }

    /**
     * @codeCoverageIgnore
     *
     * @return Fundamental
     */
    public function deleteFundamental(int $id): Fundamental
    {
        $fundamental = $this->findOrFail($id);
        $fundamental->delete();

        return $fundamental;
    }
}
