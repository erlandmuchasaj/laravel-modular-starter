<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
 *      ->createdAt(now()->subDays(2))
 *      ->withProperties([ // properties
 *          '  laravel' => 'awesome'
 *      ])
 *      ->event('verified')
 *      ->tap(function(Activity $activity) {
 *          $activity->custom_field_on_Activity_table = 'Whatever';
 *      })
 *      ->log('The subject name is :subject.name, the causer name is :causer.name and Laravel is :properties.laravel');
 *  if you want to log Anonymous logs use: causedByAnonynmous() alias byAnonymous();
 *
 * When running or using activity logger from jobs or background actions, the causer can not be retried since there
 * is no logged-in user. so for this we use a CauserResolver as below:
 *
 * use Spatie\Activitylog\Facades\CauserResolver;
 *
 * $product = Product::first(1);
 * $causer = $product->owner; # here can be used also user::find(1) or any related method to get a user.;
 *
 * CauserResolver::setCauser($causer);
 *
 * $product->update(['name' => 'New name']);
 */
trait DefaultActivityLogger
{
    use LogsActivity;

    protected static array $logAttributesToIgnore = ['id', 'password', 'remember_token', 'token', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        $model = $this->getClass();

        return LogOptions::defaults()
            ->logFillable()
            ->logUnguarded()
            ->logOnlyDirty()
            ->logAll()
            ->dontSubmitEmptyLogs()
            ->logExcept(self::$logAttributesToIgnore)
            ->useLogName($model ?: config('activitylog.default_log_name'))
            ->setDescriptionForEvent(function (string $eventName) use ($model) {
                if ($model) {
                    return  "activity_log.$model.$eventName";
                }

                return "activity_log.$eventName";
            });
    }

    public function getLogNameToUse(): ?string
    {
        if ($model = $this->getClass()) {
            return $model;
        }

        if (! empty($this->activitylogOptions->logName)) {
            return $this->activitylogOptions->logName;
        }

        return config('activitylog.default_log_name');
    }

    /**
     * @return string|null
     */
    private function getClass(): ?string
    {
        try {
            $reflect = new ReflectionClass($this);

            return Str::lower($reflect->getShortName());
        } catch(ReflectionException $e) {
            report($e);
        }

        return null;
    }
}
