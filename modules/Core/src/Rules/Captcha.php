<?php

namespace Modules\Core\Rules;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class Captcha.
 */
class Captcha implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     *
     * @throws GuzzleException
     */
    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return false;
        }

        $response = json_decode((new Client([
            'timeout' => config('boilerplate.access.captcha.configs.options.timeout'),
        ]))->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('boilerplate.access.captcha.configs.secret_key'),
                'remoteip' => request()->getClientIp(),
                'response' => $value,
            ],
        ])->getBody(), true);

        return isset($response['success']) && ($response['success'] === true);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array|null
     */
    public function message(): string|array|null
    {
        return  __('The captcha was invalid.');
    }
}
