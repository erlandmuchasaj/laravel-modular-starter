<?php

namespace Modules\Core\Events\Announcement;

use Illuminate\Broadcasting\PrivateChannel;
use Modules\Core\Events\Event;
use Modules\Core\Models\Announcement\Announcement;
use Illuminate\Database\Eloquent\Model;

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
     * @param Announcement|Model $announcement
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
