<?php

namespace Modules\Core\Rules;

use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     */
    public function passes($attribute, $value): bool
    {
        return boolval(preg_match('/^[a-z\d]+(?:-[a-z\d]+)*$/', $value));
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return trans('core::validation.slug');
    }
}
