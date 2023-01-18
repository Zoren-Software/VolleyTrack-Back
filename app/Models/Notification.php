<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function list(array $args)
    {
        return $this->userLogged()
            ->filterRead($args['read'] ?? false)
            ->orderBy('created_at', 'desc');
    }

    public function scopeUserLogged($query)
    {
        return $query->where('notifiable_id', auth()->user()->id ?? null);
    }

    public function scopeFilterRead($query, $read)
    {
        return $query->when(
            $read === true,
            fn ($query) => $query->whereNotNull('read_at')
        )
        ->when(
            $read === false,
            fn ($query) => $query->whereNull('read_at')
        );
    }
}
