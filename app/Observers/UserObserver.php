<?php

namespace App\Observers;

use App\Models\NotificationSetting;
use App\Models\NotificationType;
use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        if (!$user->isDirty('user_id')) {
            $user->user_id = auth()->user()->id ?? null;
        }
    }

    /**
     * NOTE Create notification settings default for the user
     *
     *
     * @return [type]
     */
    public function created(User $user)
    {
        $types = NotificationType::where('is_active', true)->get();

        foreach ($types as $type) {
            NotificationSetting::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'notification_type_id' => $type->id,
                ],
                [
                    'via_email' => false,
                    'via_system' => $type->allow_system,
                    'is_active' => true,
                ]
            );
        }
    }

    public function updating(User $user)
    {
        if (!$user->isDirty('user_id')) {
            $user->user_id = auth()->user()->id ?? null;
        }
    }
}
