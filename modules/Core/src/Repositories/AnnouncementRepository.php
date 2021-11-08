<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Collection;
use Modules\Core\Models\Announcement\Announcement;

/**
 * Class AnnouncementRepository.
 */
class AnnouncementRepository extends BaseRepository
{
    /**
     * AnnouncementRepository constructor.
     *
     * @param  Announcement  $announcement
     */
    public function __construct(Announcement $announcement)
    {
        $this->model = $announcement;
    }

    /**
     * Get all the enabled announcements
     * For the frontend or globally
     * Where there's either no time frame or
     * if there is a start and end date, make sure the current time is in between that or
     * if there is only a start date, make sure the current time is past that or
     * if there is only an end date, make sure the current time is before that.
     *
     * @return Collection
     */
    public function getForFrontend(): Collection
    {
        if (!config('app.announcements')) {
            return collect(new Announcement);
        }

        return $this->model::enabled()
            ->forArea($this->model::TYPE_FRONTEND)
            ->inTimeFrame()
            ->get();
    }

    /**
     * Get all the enabled announcements
     * For the backend or globally
     * Where there's either no time frame or
     * if there is a start and end date, make sure the current time is in between that or
     * if there is only a start date, make sure the current time is past that or
     * if there is only an end date, make sure the current time is before that.
     *
     * @return Collection
     */
    public function getForBackend(): Collection
    {

        if (!config('app.announcements')) {
            return collect(new Announcement);
        }

        return $this->model::enabled()
            ->forArea($this->model::TYPE_BACKEND)
            ->inTimeFrame()
            ->get();
    }
}
