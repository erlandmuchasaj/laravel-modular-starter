<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Str;

/**
 * A trait to generate automatically UUID-s for models that use uuid as primary kay.
 */
trait Uuid
{
    public static function bootUuid()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
