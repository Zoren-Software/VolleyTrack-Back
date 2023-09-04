<?php

namespace App\Models;

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterCPF(Builder $query, string $cpf)
    {
        $cleanCpf = preg_replace('/\D/', '', $cpf);
        $query->when(isset($cleanCpf), function ($query) use ($cleanCpf) {
            $query->where('user_information.cpf', 'like', "%$cleanCpf%");
        });
    }

    public function scopeFilterRG(Builder $query, string $rg)
    {
        $cleanRg = preg_replace('/\D/', '', $rg);
        $query->when(isset($cleanRg), function ($query) use ($cleanRg) {
            $query->where('user_information.rg', 'like', "%$cleanRg%");
        });
    }

    public function scopeFilterPhone(Builder $query, string $phone)
    {
        $cleanPhone = preg_replace('/\D/', '', $phone);
        $query->when(isset($cleanPhone), function ($query) use ($cleanPhone) {
            $query->where('user_information.phone', 'like', "%$cleanPhone%");
        });
    }
}
