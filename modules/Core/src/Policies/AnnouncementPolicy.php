<?php

namespace Modules\Core\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Models\Announcement\Announcement;

class AnnouncementPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @description Gates are most applicable to actions which are not related to any model or resource,
     * such as viewing an administrator dashboard.
     * In contrast, policies should be used when you wish to authorize an action for a particular model or resource.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any Announcements.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the Announcement.
     *
     * @param  User|null $user
     * @param  Announcement  $announcement
     * @return bool
     */
    public function view(?User $user, Announcement $announcement): bool
    {
        if ($announcement->published) {
            return true;
        }

        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        if ($user->can('view unpublished Announcements')) {
            return true;
        }

        // authors can view their own unpublished Announcements
        return $user->id == $announcement->user_id;
    }

    /**
     * Determine whether the user can create Announcements.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        if ($user->can('create Announcements')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the Announcement.
     *
     * @param  User  $user
     * @param  Announcement  $announcement
     * @return bool
     */
    public function update(User $user, Announcement $announcement): bool
    {
        if ($user->can('edit own Announcements')) {
            return $user->id == $announcement->user_id;
        }

        if ($user->can('edit all Announcements')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the Announcement.
     *
     * @param  User  $user
     * @param  Announcement  $announcement
     * @return bool
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        if ($user->can('delete own announcements')) {
            return $user->id == $announcement->user_id;
        }

        if ($user->can('delete any Announcement')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the Announcement.
     *
     * @param  User  $user
     * @param  Announcement  $announcement
     * @return bool
     */
    public function restore(User $user, Announcement $announcement): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the Announcement.
     *
     * @param  User  $user
     * @param  Announcement  $announcement
     * @return bool
     */
    public function forceDelete(User $user, Announcement $announcement): bool
    {
        return true;
    }
}
