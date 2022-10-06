<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SpecificFundamental extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

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

    /**
     * @codeCoverageIgnore
     *
     * @return SpecificFundamental
     */
    public function deleteSpecificFundamental(int $id): SpecificFundamental
    {
        $specificFundamental = $this->findOrFail($id);
        $specificFundamental->delete();

        return $specificFundamental;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->table)
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(
                [
                    'updated_at',
                    'created_at',
                    'deleted_at'
                ]
            )
            ->dontSubmitEmptyLogs();
    }
}
