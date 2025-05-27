<?php

namespace App\Models;

use App\Observers\Logs\UserInformationObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \App\Models\User $user
 */
class UserInformation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cpf',
        'phone',
        'rg',
        'birth_date',
    ];

    protected static function boot()
    {
        parent::boot();
        UserInformation::observe(UserInformationObserver::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter(Builder $query, string $search)
    {
        $query->when(!empty($search), function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $this->applyCPF($query, $search);
                $this->applyRG($query, $search);
                $this->applyPhone($query, $search);
            });
        });
    }

    private function applyCPF(Builder $query, string $search): void
    {
        $cleanCpf = preg_replace('/\D/', '', $search);
        $query->where('cpf', 'like', "%$cleanCpf%");
    }

    private function applyRG(Builder $query, string $search): void
    {
        $cleanRg = preg_replace('/\D/', '', $search);
        $query->orWhere('user_information.rg', 'like', "%$cleanRg%");
    }

    private function applyPhone(Builder $query, string $search): void
    {
        $cleanPhone = preg_replace('/\D/', '', $search);
        $query->orWhere('user_information.phone', 'like', "%$cleanPhone%");
    }
}
