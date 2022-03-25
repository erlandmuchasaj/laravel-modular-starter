<?php

namespace Modules\Core\Repositories;


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Events\Announcement\AnnouncementCreated;
use Modules\Core\Events\Announcement\AnnouncementDeleted;
use Modules\Core\Events\Announcement\AnnouncementDisabled;
use Modules\Core\Events\Announcement\AnnouncementEnabled;
use Modules\Core\Events\Announcement\AnnouncementPermanentDeleted;
use Modules\Core\Events\Announcement\AnnouncementRestored;
use Modules\Core\Events\Announcement\AnnouncementUpdated;
use Modules\Core\Exceptions\GeneralException;
use Modules\Core\Models\Announcement\Announcement;
use Throwable;

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
     * @param array $data
     *
     * @throws GeneralException
     * @throws Exception
     * @throws Throwable
     *
     * @return Announcement
     */
    public function create(array $data): Announcement
    {

        return DB::transaction(function () use ($data) {
            // Before create event
            $announcement = $this->model::create($data);

            if ($announcement) {

               // Fire announcement created event after create event
               event(new AnnouncementCreated($announcement));

               // Return the country object
                return $announcement;
            }

            throw new GeneralException(__('There was a problem creating announcement.'));
        }, 3);
    }

    /**
     * @param Announcement  $announcement
     * @param array $data
     *
     * @throws GeneralException
     * @throws Exception
     * @throws Throwable
     *
     * @return Announcement
     */
    public function update(Announcement $announcement, array $data) : Announcement
    {
        return DB::transaction(function () use ($announcement, $data) {
            if ($announcement->update($data)) {
                // Add selected roles/permissions
                event(new AnnouncementUpdated($announcement));

                return $announcement;
            }
            throw new GeneralException(__('Announcement could not be updated'));
        }, 3);
    }

    /**
     * @param Announcement  $announcement
     * @param int  $status
     *
     * @throws GeneralException
     * @throws Exception
     * @throws Throwable
     *
     * @return Announcement
     */
    public function mark(Announcement $announcement, int $status) : Announcement
    {

        return DB::transaction(function () use ($announcement, $status) {

            $announcement->enabled = $status;

            if ($announcement->save()) {

                switch ($status) {
                    case 0:
                        event(new AnnouncementDisabled($announcement));
                        break;
                    case 1:
                        event(new AnnouncementEnabled($announcement));
                        break;
                }

                return $announcement;
            }

            throw new GeneralException(__('Announcement status could not be changed!'));
        }, 3);
    }

    /**
     * @param Announcement  $announcement
     *
     * @throws GeneralException
     * @throws Exception
     * @throws Throwable
     *
     * @return bool
     */
    public function delete(Announcement $announcement): bool
    {
        return DB::transaction(function () use ($announcement) {
            // Soft Delete associated relationships if any

            if ($announcement->delete()) {

                event(new AnnouncementDeleted($announcement));

                return true;
            }

            throw new GeneralException(__('Announcement could not be deleted'));
        }, 3);
    }

    /**
     * @param Announcement  $announcement
     *
     * @throws GeneralException
     * @throws Exception
     * @throws Throwable
     *
     * @return Announcement
     */
    public function restore(Announcement $announcement) : Announcement
    {
        if ($announcement->deleted_at === null) {
            throw new GeneralException(__('Announcement is already restored.'));
        }

        return DB::transaction(function () use ($announcement) {

            if ($announcement->restore()) {

                event(new AnnouncementRestored($announcement));

                return $announcement;
            }

            throw new GeneralException(__('Announcement can not be restored'));
        }, 3);
    }

    /**
     * @param Announcement  $announcement
     *
     * @throws GeneralException
     * @throws Exception
     * @throws Throwable
     *
     * @return Announcement
     */
    public function forceDelete(Announcement $announcement) : Announcement
    {
        if ($announcement->deleted_at === null) {
            throw new GeneralException(__('Announcement should be deleted first.'));
        }

        return DB::transaction(function () use ($announcement) {
            // Delete associated relationships if any

            if ($announcement->forceDelete()) {

                event(new AnnouncementPermanentDeleted($announcement));

                return $announcement;
            }

            throw new GeneralException(__('Announcement could not be permanently deleted.'));
        }, 3);
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

        return Cache::remember("get_for_frontend_announcements", now()->addMinutes(20), function () {
            return $this->model::enabled()
                ->forArea($this->model::TYPE_FRONTEND)
                ->inTimeFrame()
                ->get();
        });
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

        cache()->remember('get_for_backend', now()->addMinutes(20), function () {
            return $this->model::enabled()
                ->forArea($this->model::TYPE_BACKEND)
                ->inTimeFrame()
                ->get();
        });
    }
}
