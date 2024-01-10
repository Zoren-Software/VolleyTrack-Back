<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        if (!$user->isDirty('user_id')) {
            $user->user_id = auth()->user()->id ?? null;
        }
    }

    public function updating(User $user)
    {
        if (!$user->isDirty('user_id')) {
            $user->user_id = auth()->user()->id ?? null;
        }
    }
}
