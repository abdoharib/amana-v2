<?php

namespace App\Policies;

use App\Models\Advice;
use App\Models\User;

class AdvicePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Advice $advice)
    {
        return $user->hasRole('Admin') || $advice->guardian_id === $user->id;
    }

    public function update(User $user, Advice $advice)
    {
        return $user->hasRole('Admin');
    }

    public function delete(User $user, Advice $advice)
    {
        return $user->hasRole('Admin');
    }
}
