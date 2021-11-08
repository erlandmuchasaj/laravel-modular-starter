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
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function retrieved(Announcement $announcement) : void
    {
        //
        logger('Announcement retrieved');
    }

    /**
     * Handle the Model "creating" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function creating(Announcement $announcement) : void
    {
        //
        logger('Announcement creating');
    }

    /**
     * Handle the Model "created" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function created(Announcement $announcement) : void
    {
        //
        logger('Announcement created');
    }

    /**
     * Handle the Model "updating" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function updating(Announcement $announcement) : void
    {
        //
        logger('Announcement updating');
    }

    /**
     * Handle the Model "updated" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function updated(Announcement $announcement) : void
    {
        //
        logger('Announcement updated');
    }

    /**
     * Handle the Model "saving" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function saving(Announcement $announcement) : void
    {
        //
        logger('Announcement saving');
    }

    /**
     * Handle the Model "saved" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function saved(Announcement $announcement) : void
    {
        //
        logger('Announcement saved');
    }

    /**
     * Handle the Model "deleting" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function deleting(Announcement $announcement) : void
    {
        //
        logger('Announcement deleting');
    }

    /**
     * Handle the Model "deleted" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function deleted(Announcement $announcement) : void
    {
        //
        logger('Announcement deleted');
    }

    /**
     * Handle the Model "restoring" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function restoring(Announcement $announcement) : void
    {
        //
        logger('Announcement restoring');
    }

    /**
     * Handle the Model "restored" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function restored(Announcement $announcement) : void
    {
        //
        logger('Announcement restored');
    }

    /**
     * Handle the Model "force deleted" event.
     *
     * @param  Announcement $announcement
     * @return void
     */
    public function forceDeleted(Announcement $announcement) : void
    {
        //
        logger('Announcement forceDeleted');
    }
}
