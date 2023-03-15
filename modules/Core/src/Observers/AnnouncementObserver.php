<?php

namespace Modules\Core\Observers;

use Modules\Core\Models\Announcement\Announcement;

/**
 * Class AnnouncementObserver.
 */
class AnnouncementObserver
{
    /**
     * Handle the Model "retrieved" event.
     */
    public function retrieved(Announcement $announcement): void
    {
        //
        logger('Announcement retrieved');
    }

    /**
     * Handle the Model "creating" event.
     */
    public function creating(Announcement $announcement): void
    {
        //
        logger('Announcement creating');
    }

    /**
     * Handle the Model "created" event.
     */
    public function created(Announcement $announcement): void
    {
        //
        logger('Announcement created');
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(Announcement $announcement): void
    {
        //
        logger('Announcement updating');
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Announcement $announcement): void
    {
        //
        logger('Announcement updated');
    }

    /**
     * Handle the Model "saving" event.
     */
    public function saving(Announcement $announcement): void
    {
        //
        logger('Announcement saving');
    }

    /**
     * Handle the Model "saved" event.
     */
    public function saved(Announcement $announcement): void
    {
        //
        logger('Announcement saved');
    }

    /**
     * Handle the Model "deleting" event.
     */
    public function deleting(Announcement $announcement): void
    {
        //
        logger('Announcement deleting');
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Announcement $announcement): void
    {
        //
        logger('Announcement deleted');
    }

    /**
     * Handle the Model "restoring" event.
     */
    public function restoring(Announcement $announcement): void
    {
        //
        logger('Announcement restoring');
    }

    /**
     * Handle the Model "restored" event.
     */
    public function restored(Announcement $announcement): void
    {
        //
        logger('Announcement restored');
    }

    /**
     * Handle the Model "force deleted" event.
     */
    public function forceDeleted(Announcement $announcement): void
    {
        //
        logger('Announcement forceDeleted');
    }
}
