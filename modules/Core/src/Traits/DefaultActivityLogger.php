<?php

namespace Modules\Core\Traits;

use ReflectionClass;
use ReflectionException;
use Illuminate\Support\Str;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

/**
 *  activity('user')
 *      ->performedOn($article) // subject  alias on()
 *      ->causedBy($user) // causer alias by()
 *      ->withProperties([ // properties
 *          '  laravel' => 'awesome'
 *      ])
 *      ->log('The subject name is :subject.name, the causer name is :causer.name and Laravel is :properties.laravel');
 *
 *   activity('logs')
 *      ->on($article) // subject  alias on()
 *      ->by($user) // causer alias by()
 *      ->withProperties([ // properties
 *          '  laravel' => 'awesome'
 *      ])
 *      ->event('verified')
 *      ->log('The subject name is :subject.name, the causer name is :causer.name and Laravel is :properties.laravel');
 * if you want to log Anonymous logs use: causedByAnonynmous() alias byAnonymous()
 */
trait DefaultActivityLogger
{
    use LogsActivity;

    protected static bool $logFillable = true;

    protected static bool $logUnguarded = true;

    protected static bool $logOnlyDirty = true;

    protected static bool $submitEmptyLogs = false;

    protected static array $logAttributes = ['*'];

    protected static array $logAttributesToIgnore = ['id', 'password', 'remember_token', 'token', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @param Activity $activity
     * @param string $eventName
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        try {
            $reflect = new ReflectionClass($this);
            $class_name = Str::lower($reflect->getShortName());
            $activity->description = "$class_name.{$eventName}";
        } catch(ReflectionException $e) {
            $activity->description = $eventName;
        }
    }


    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

}
