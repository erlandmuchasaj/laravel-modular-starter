<?php

namespace Modules\Core\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Enums\Notification;
use Throwable;

class GeneralException extends Exception
{
    /**
     * Error message
     *
     * @var string
     */
    public $message;

    /**
     * GeneralException constructor.
     *
     * @param  string  $message Bad request
     * @param  int  $code 400
     */
    public function __construct(string $message = 'Bad Request', int $code = 400, Throwable $previous = null)
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
     */
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        // All instances of ReportableException redirect back with a flash message to show a bootstrap alert-error
        $response = Notification::error($this->message, __('core::notification.error'));

        if ($request->expectsJson()) {
            return response()->json($response, ($this->code ?: \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST));
        }

        return redirect()
            ->back()
            ->withInput()
            ->with([Notification::NAME => json_encode($response)]);
    }
}
