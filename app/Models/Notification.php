<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    /**
     * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\NotificationFactory>
     */
    use HasFactory;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        /* 'id', */
        'read_at',
    ];

    /**
     * @param array<string, mixed> $args
     * 
     * @return Builder<Notification>
     */
    public function list(array $args): Builder
    {
        return $this->userLogged()
            ->filterRead($args['read'] ?? false)
            ->orderBy('created_at', 'desc');
    }

    /**
     * @param Builder<Notification> $query
     * 
     * @return Builder<Notification>
     */
    public function scopeUserLogged(Builder $query): Builder
    {
        return $query->where('notifiable_id', auth()->user()->id ?? null);
    }

    /**
     * @param Builder<Notification> $query
     * @param bool $read
     * 
     * @return Builder<Notification>
     */
    public function scopeFilterRead(Builder $query, bool $read): Builder
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

    /**
     * @return BelongsTo<User, Notification>
     */
    public function userNotifiable(): BelongsTo
    {
        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class, 'notifiable_id');
    }
}
