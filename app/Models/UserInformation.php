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
        'rg'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterCPF(Builder $query, string $cpf)
    {
        $query->when(isset($cpf), function ($query) use ($cpf) {
            $query->where('user_information.cpf', 'like', $cpf);
        });
    }

    public function scopeFilterRG(Builder $query, string $rg)
    {
        $query->when(isset($rg), function ($query) use ($rg) {
            $query->where('user_information.rg', 'like', $rg);
        });
    }

    public function scopeFilterPhone(Builder $query, string $phone)
    {
        $query->when(isset($phone), function ($query) use ($phone) {
            $query->where('user_information.phone', 'like', $phone);
        });
    }
}
