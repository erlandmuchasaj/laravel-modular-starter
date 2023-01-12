<?php

namespace Modules\Core\Enums;

use Illuminate\Support\Str;
final class Notification
{
    const NAME = 'notification';

    const INFO = 'info';

    const ERROR = 'error';

    const WARNING = 'warning';

    const SUCCESS = 'success';

    public static function info(string $message, ?string $title = null): array
    {
        return self::add(self::INFO, $message, $title);
    }

    public static function success(string $message, ?string $title = null): array
    {
        return self::add(self::SUCCESS, $message, $title);
    }

    public static function warning(string $message, ?string $title = null): array
    {
        return self::add(self::WARNING, $message, $title);
    }

    public static function error(string $message, ?string $title = null): array
    {
        return self::add(self::ERROR, $message, $title);
    }

    public static function add(string $type, string $message, ?string $title = null): array
    {
        return  self::formatNotification($type, $message, $title);
    }

    private static function formatNotification(string $type, string $message, ?string $title = null): array
    {
        return [
            'type' => $type,
            'title' => $title ?: Str::ucfirst($type),
            'message' => $message,
        ];
    }
}
