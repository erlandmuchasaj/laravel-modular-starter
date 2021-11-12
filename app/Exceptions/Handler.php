<?php

namespace App\Exceptions;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Modules\Core\Enums\Notification;
use Modules\Core\Exceptions\GeneralException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        GeneralException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //    throwable
        });
    }

    public function report(Throwable $e)
    {
        if (config('app.sentry_support') && app()->environment('production')) {
            if (app()->bound('sentry') && $this->shouldReport($e)) {
                app('sentry')->captureException($e);
            }
        }
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {

        if ($e instanceof AuthorizationException) {

            $response = [
                'type' => Notification::ERROR,
                'title' => __('notification.error'),
                'message' => $e->getMessage() ?: __('You do not have access to do that.'),
            ];

            if ($request->expectsJson()) {
                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            }

            return redirect()
                ->back()
                ->with([Notification::NAME => json_encode($response)]);
        }

        if ($e instanceof ModelNotFoundException) {
            $response = [
                'type' => Notification::ERROR,
                'title' => __('notification.error'),
                'message' => __('The requested resource was not found.'),
            ];

            if ($request->expectsJson()) {
                return response()->json($response, Response::HTTP_NOT_FOUND);
            }

            return redirect()
                ->route(RouteServiceProvider::HOME)
                ->with([Notification::NAME => json_encode($response)]);
        }

        return parent::render($request, $e);
    }

}
