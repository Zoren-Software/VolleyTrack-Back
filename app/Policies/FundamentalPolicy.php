<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FundamentalPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-fundamental');
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-fundamental');
    }
}
