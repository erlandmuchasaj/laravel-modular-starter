<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Event;
use Modules\Core\Enums\Notification;
use Modules\Core\Exceptions\GeneralException;
use Modules\Core\Http\Requests\Announcement\ManageAnnouncementRequest;
use Modules\Core\Http\Resources\AnnouncementResource;
use Modules\Core\Repositories\AnnouncementRepository;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class AnnouncementController.
 */
class AnnouncementController extends Controller
{
    /**
     * @var AnnouncementRepository
     */
    protected AnnouncementRepository $announcementRepository;

    /**
     * UserController constructor.
     *
     * @param  AnnouncementRepository  $announcementRepository
     */
    public function __construct(AnnouncementRepository $announcementRepository)
    {
        $this->$announcementRepository = $announcementRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ManageAnnouncementRequest  $request
     * @return RedirectResponse|JsonResponse
     *
     * @throws GeneralException
     * @throws Throwable
     */
    public function store(ManageAnnouncementRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();

        Event::dispatch('core.announcement.create.before');

        $announcement = $this->announcementRepository->create($validated);

        Event::dispatch('core.announcement.create.after', $announcement);

        // To listen fo specific events we listen on boot() method os EventServiceProvider
        // Event::listen('core.announcement.create.after', 'Modules\Core\Listeners\Announcement@sendNewAnnouncementMail');

        if ($request->expectsJson()) {
            // For rest API responses
            return (new AnnouncementResource($announcement))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        }

        // For SSR responses
        $response = [
            'type' => Notification::SUCCESS,
            'title' => __('notification.success'),
            'message' => __('Announcement Created'),
        ];

        return redirect()->route('home')->with([Notification::NAME => json_encode($response)]);
    }
}
