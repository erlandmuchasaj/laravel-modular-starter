<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Core\Enums\Notification;

/**
 * Class LanguageController.
 */
class LanguageController extends Controller
{
    /**
     * @param  Request  $request
     * @param  string  $locale
     * @return RedirectResponse|JsonResponse
     */
    public function swap(Request $request, string $locale): RedirectResponse|JsonResponse
    {
        // if (config('app.locale_status') && array_key_exists($locale, config('app.locales'))) {
        if (config('app.locale_status') && locales()->has($locale)) {
            session()->put('locale', $locale);
        }

        $response = [
            'type' => Notification::ERROR,
            'title' => __('notification.error'),
            'message' => __('Language changed'),
        ];

        if (! $request->expectsJson()) {
            return redirect()->back()->with([Notification::NAME => json_encode($response)]);
        }

        return response()->json($response);
    }
}
