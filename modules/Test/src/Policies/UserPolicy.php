<?php

namespace Modules\Test\Policies;

use Illuminate\Auth\Access\Response;
use Modules\Test\Models\User\User;
use Modules\User\Models\User\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @description Gates are most applicable to actions which are not related to any model or resource,
     * such as viewing an administrator dashboard.
     * In contrast, policies should be used when you wish to authorize an action for a particular model or resource.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        //
    }
}
