<?php

namespace Modules\Core\Rules;

use Illuminate\Contracts\Validation\Rule;

class Decimal implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     */
    public function passes($attribute, $value): bool
    {
        return boolval(preg_match('/^\d*(\.\d{1,4})?$/', $value));
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return trans('core::validation.decimal');
    }
}
