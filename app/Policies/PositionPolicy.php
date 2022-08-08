<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PositionPolicy
{
    use HandlesAuthorization;
    
    private $user;
    
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(): bool
    {
        return $this->user->hasPermissionTo('create-position');
    }

    public function edit(): bool
    {
        return $this->user->hasPermissionTo('edit-position');
    }
}
