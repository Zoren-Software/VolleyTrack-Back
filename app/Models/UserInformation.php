<?php

namespace App\Models;

use App\Observers\Logs\UserInformationObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter(Builder $query, string $search)
    {
        $query->when(isset($search), function ($query) use ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->filterCPF($search)
                    ->filterRG($search)
                    ->filterPhone($search);
            });
        });
    }

    public function scopeFilterCPF(Builder $query, string $cpf)
    {
        $cleanCpf = preg_replace('/\D/', '', $cpf);
        $query->when(isset($cleanCpf), function ($query) use ($cleanCpf) {
            $query->where('cpf', 'like', "%$cleanCpf%");
        });
    }

    public function scopeFilterRG(Builder $query, string $rg)
    {
        $cleanRg = preg_replace('/\D/', '', $rg);
        $query->when(isset($cleanRg), function ($query) use ($cleanRg) {
            $query->orWhere('user_information.rg', 'like', "%$cleanRg%");
        });
    }

    public function scopeFilterPhone(Builder $query, string $phone)
    {
        $cleanPhone = preg_replace('/\D/', '', $phone);
        $query->when(isset($cleanPhone), function ($query) use ($cleanPhone) {
            $query->orWhere('user_information.phone', 'like', "%$cleanPhone%");
        });
    }
}
