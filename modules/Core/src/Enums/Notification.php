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


    public static function add(string $type, string $message, ?string $title): array
    {
        return  self::formatNotification($type, $message, $title);
    }

    public static function info(string $message, ?string $title): void
    {
        self::add(self::INFO, $message, $title);
    }

    public static function success(string $message, ?string $title): void
    {
        self::add(self::SUCCESS, $message, $title);
    }

    public static function warning(string $message, ?string $title): void
    {
        self::add(self::WARNING, $message, $title);
    }

    public static function error(string $message, ?string $title): void
    {
        self::add(self::ERROR, $message, $title);
    }

    private static function formatNotification(string $type, string $message, ?string $title): array
    {
        return [
            'type'    => $type,
            'title'   => $title ?: Str::ucfirst($type),
            'message' => $message,
        ];
    }

}

