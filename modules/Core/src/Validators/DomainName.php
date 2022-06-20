<?php

namespace Modules\Core\Validators;

use Illuminate\Validation\Validator;

class DomainName
{
    /**
     * Validates whether it is a valid domain name.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool
    {

        if (stripos($value, 'http://') === 0) {
            $value = substr($value, 7);
        }

        if (stripos($value, 'https://') === 0) {
            $value = substr($value, 8);
        }

        // Not even a single . this will eliminate things like abcd, since http://abcd is reported valid
        if (!substr_count($value, '.')) {
            return false;
        }

        if (stripos($value, 'www.') === 0) {
            $value = substr($value, 4);
        }

        // remove last /
        $value = rtrim($value, '/\\');

        return (preg_match("/^([a-z\d](-*[a-z\d])*)(.([a-z\d](-*[a-z\d])*))*$/i", $value) //valid characters check
            && preg_match("/^.{1,253}$/", $value) //overall length check
            && preg_match("/^[^.]{1,63}(.[^.]{1,63})*$/", $value)); //length of every label
    }
}
