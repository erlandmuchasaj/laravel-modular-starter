<?php

namespace Modules\Core\Rules;

use Illuminate\Contracts\Validation\Rule;

class Code implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return boolval(preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]+$/', $value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('core::validation.code');
    }
}
