<?php

namespace Modules\Core\Events\Announcement;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Events\Event;
use Modules\Core\Models\Announcement\Announcement;

/**
 * Class AnnouncementEvent.
 */
class AnnouncementEvent extends Event
{
    /**
     * @var Announcement|Model
     */
    public Announcement|Model $announcement;

    /**
     * @param  Announcement|Model  $announcement
     */
    public function __construct(Announcement|Model $announcement)
    {
        $this->$announcement = $announcement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('channel-name');
    }
}
