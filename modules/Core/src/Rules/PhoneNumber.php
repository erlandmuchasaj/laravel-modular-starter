<?php

namespace Modules\Core\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        return boolval(preg_match('/^\+[1-9]\d{1,14}$/', $value));
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('Phone number format is invalid.');
    }
}
