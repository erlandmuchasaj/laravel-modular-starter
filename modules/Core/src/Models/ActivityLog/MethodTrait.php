<?php

namespace Modules\Core\Models\ActivityLog;

use Illuminate\Support\Str;
use Modules\User\Models\User\User;
use ReflectionClass;
use ReflectionException;

trait MethodTrait
{
    /**
     * @param $description
     * @param $subject
     * @param $causer
     * @param $causer_type
     * @return string|null
     */
    private function getTranslationMessage($description, $subject, $causer, $causer_type): string|null
    {
        if ($description === null) {
            return null;
        }

        if (Str::contains($description, 'activity_log.')) {
            return __("core::$description");
        }

        if (
            $subject !== null &&
            $causer !== null &&
            $causer_type === (new User)->getMorphClass()
        ) {
            $attr = $this->getClassNameAndEvent($description, $subject);

            if (is_array($attr)) {
                $user = $causer->name;

                if ($causer->id === auth()->id()) {
                    $user = __('core::activity_log.user.you');
                }

                return __("core::activity_log.events.user.{$attr['event']}", [
                    'model' => __("core::activity_log.models.{$attr['model']}"),
                    'user' => $user,
                ]);
            }
        }

        if ($subject !== null && $causer === null) {
            $attr = $this->getClassNameAndEvent($description, $subject);

            if (is_array($attr)) {
                return __("core::activity_log.events.no_user.{$attr['event']}", [
                    'model' => __("core::activity_log.models.{$attr['model']}"),
                ]);
            }
        }

        return null;
    }

    /**
     * @param  string  $description
     * @param  mixed  $model
     * @return array|null
     */
    private function getClassNameAndEvent(string $description, mixed $model): ?array
    {
        try {
            $reflect = new ReflectionClass($model);

            $class_name = Str::lower($reflect->getShortName());

            $event_name = str_replace("$class_name.", '', $description);

            // dd(func_get_args(), $reflect->getShortName(), $reflect->getNamespaceName(), $reflect->getName(),
            //    $class_name,$event_name);

            if (
                $class_name !== null &&
                strlen($class_name) > 0 &&
                strlen($event_name) > 0 &&
                in_array($event_name, $this->allowedEvents())
            ) {
                return [
                    'model' => $class_name,
                    'event' => $event_name,
                ];
            }

            return null;
        } catch(ReflectionException $e) {
            return null;
        }
    }

    /**
     * @return array
     */
    private function allowedEvents(): array
    {
        return [
            // Eloquent Events
            'retrieved',
            'creating',
            'created',
            'updating',
            'updated',
            'saving',
            'saved',
            'deleting',
            'deleted',
            'restoring',
            'restored',

            // Custom Events
            'login',
            'logout',
            'checked',
            'unchecked',
            'copied',
            'blocked',
            'unblocked',
            'followed',
            'unfollowed',
        ];
    }
}
