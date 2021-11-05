<?php

namespace Modules\Core\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Enums\Notification;
use Throwable;
use Exception;

class GeneralException extends Exception
{
    /**
     * Error message
     * @var string
     */
    public $message;

    /**
     * GeneralException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        // All instances of ReportableException redirect back with a flash message to show a bootstrap alert-error
        $response = [
            'type'    => Notification::ERROR,
            'title'   => __('notification.error'),
            'message' => $this->message,
        ];

        if ($request->expectsJson()) {
            return response()->json($response, ($this->code ?: 400));
        }

        return redirect()
            ->back()
            ->withInput()
            ->with([Notification::NAME => json_encode($response)]);
    }
}
