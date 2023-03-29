<?php

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

if (! function_exists('locale')) {
    function locale(string $locale = null): string
    {
        if (is_null($locale)) {
            return app()->getLocale();
        }

        app()->setLocale($locale);

        return app()->getLocale();
    }
}

if (! function_exists('locales')) {
    /**
     * @return Collection<string, array>
     */
    function locales(): Collection
    {
        /** @var array<string, array> $locales */
        $locales = config('app.locales', []);
        return collect($locales);
    }
}

if (! function_exists('setAllLocale')) {
    /**
     * setAllLocale
     * Set application global locale
     */
    function setAllLocale(string $locale): void
    {
        if (App::currentLocale() !== $locale) {
            $lang = locales()->first(function ($value, $key) use ($locale) {
                return $key === $locale;
            });

            // set laravel localization (lumen)
            app('translator')->setLocale($locale);

            // Set the Laravel locale
            App::setLocale($locale);

            // setLocale to use Carbon source locales. Enables diffForHumans() localized
            Carbon::setLocale($locale);

            // setLocale for php. Enables ->formatLocalized() with localized values for dates
            setlocale(LC_TIME, str_replace('-', '_', $lang['code']));

            /*
             * Set the session variable for whether the app is using RTL support
             * For use in the blade directive in BladeServiceProvider
             */
            if (! app()->runningInConsole()) {
                // $lang[2])
                if ($lang['rtl']) {
                    session(['lang-rtl' => true]);
                } else {
                    session()->forget('lang-rtl');
                }
            }
        }
    }
}

if (! function_exists('gravatar')) {
    /**
     * Gravatar URL
     * Generate a gravatar for a user
     */
    function gravatar(string $name): string
    {
        $gravatarId = md5(strtolower(trim($name)));

        return 'https://gravatar.com/avatar/'.$gravatarId.'?s=90';
    }
}

if (! function_exists('display_price')) {
    /**
     * display_price
     * convert price with decimals and separator
     *
     *
     * @$price - Added hack in for when the variants are being created it passes over the new ISO currency code
     * which breaks number_format
     */
    function display_price(int|string $price, int $decimals = 2): string
    {
        if (! is_numeric($price)) {
            return $price;
        }

        $price = preg_replace("/^(\d+\.?\d*)(\s[A-Z]{3})$/", '$1', (string) $price);
        // return number_format((float) $price, $decimals, '.', ',');
        return number_format((float) $price, $decimals);
    }
}

if (! function_exists('diff_for_humans')) {
    function diff_for_humans(Carbon $date): string
    {
        return $date->diffForHumans();
    }
}

if (! function_exists('convertToLocal')) {
    /**
     * Used when displaying dates to frontend convert date from UTC to Local time.
     * Display dates in user timezone on Frontend - Convert UTC time to User time
     *
     * @param  mixed|null  $date - practically the date in UTC timezone or coming from DB
     * @param  string  $fromFormat - the default format in our DB
     *
     * @throws InvalidFormatException
     *
     * @example Carbon::now($userTimezone)->setTimezone(config('app.timezone'))
     * @example $query->where(‘from’, Carbon::now($userTimezone)->setTimezone(config(‘app.timezone’))) *
     */
    function convertToLocal(mixed $date = null, string $fromFormat = 'Y-m-d H:i:s'): ?Carbon
    {
        if (is_null($date)) {
            return $date;
        }

        // $userTimezone = optional(auth()->user())->timezone ?? config('app.timezone');
        $userTimezone = auth()->user()->timezone ?? config('app.timezone');

        if (! ($date instanceof Carbon)) {
            if (is_numeric($date)) {
                // assuming is a timestamp 12547896857
                $date = Carbon::createFromTimestamp($date, config('app.timezone'));
            } else {
                // $date = new Carbon($date, config('app.timezone'));
                // $date = Carbon::parse($date, config('app.timezone'));
                $date = Carbon::createFromFormat($fromFormat, $date, config('app.timezone'));
            }
        }

        return $date->setTimezone($userTimezone);
    }
}

if (! function_exists('convertFromLocal')) {
    /**
     * convertFromLocal aka ConvertDateFromLocalToUTC
     *
     * Saving the users input (Local time) to the database in UTC - Convert user time to UTC
     * This will take a date/time, set it to the users' timezone then return it as UTC in a Carbon instance.
     *
     *
     * @throws InvalidFormatException
     */
    function convertFromLocal(mixed $date = null, string $fromFormat = 'Y-m-d H:i:s'): ?Carbon
    {
        if (is_null($date)) {
            return $date;
        }

        // by default, we check if there is a user timezone, if not we get the default timezone.
        // $userTimezone = optional(auth()->user())->timezone ?? config('app.timezone');
        $userTimezone = auth()->user()->timezone ?? config('app.timezone');

        if (! ($date instanceof Carbon)) {
            if (is_numeric($date)) {
                // assuming is a timestamp 12547896857
                $date = Carbon::createFromTimestamp($date, $userTimezone);
            } else {
                // $date = new Carbon($date, $userTimezone);
                // $date = Carbon::parse($date, $userTimezone);
                $date = Carbon::createFromFormat($fromFormat, $date, $userTimezone);
            }
        }

        return $date->setTimezone(config('app.timezone'));
    }
}

if (! function_exists('storage_asset')) {
    /**
     * Check if a storage file exists and return its URL.
     *
     * @param  string|null  $disk local|public|s3
     * @param  string  $type type of default image image|file|document|audio|video|avatar|not-available
     * @return string file path
     *
     * @todo implement another method on file upload to increase performance
     */
    function storage_asset(string $path = null, string $disk = null, string $type = 'not-available'): string
    {
        if ($path !== null) {
            if ($disk === null) {
                $disk = config('filesystems.default');
            }

            return Storage::disk($disk)->url($path);
        }

        return match ($type) {
            'avatar' => asset('/img/default-avatar.png'),
            'audio' => asset('/img/files/audio.png'),
            'video' => asset('/img/files/video.png'),
            'image' => asset('/img/files/image.png'),
            'document' => asset('/img/files/document.png'),
            'file' => asset('/img/files/file.png'),
            default => asset('/img/no-image-available.jpg'),
        };
    }
}

if (! function_exists('getNWords')) {
    /**
     * Limit content with number of words
     */
    function getNWords(string $string, int $n = 5, bool $withDots = true): string
    {
        $excerpt = explode(' ', strip_tags($string), $n + 1);
        $wordCount = count($excerpt);
        if ($wordCount >= $n) {
            array_pop($excerpt);
        }
        $excerpt = implode(' ', $excerpt);
        if ($withDots && $wordCount >= $n) {
            $excerpt .= '...';
        }

        return $excerpt;
    }
}

if (! function_exists('getFacebookShareLink')) {
    /**
     * Get Facebook share link
     */
    function getFacebookShareLink(string $url, string $title): string
    {
        return 'https://www.facebook.com/sharer/sharer.php?u='.$url.'&t='.rawurlencode($title);
    }
}

if (! function_exists('getTwitterShareLink')) {
    /**
     * Get Twitter share link
     */
    function getTwitterShareLink(string $url, string $title): string
    {
        return 'https://twitter.com/intent/tweet?text='.rawurlencode(implode(' ', [$title, $url]));
    }
}

if (! function_exists('roman_year')) {
    function roman_year(int $year = null): string
    {
        $year = $year ?? date('Y');

        $romanNumerals = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1,
        ];

        $result = '';

        foreach ($romanNumerals as $roman => $yearNumber) {
            // Divide to get  matches
            $matches = (int) ($year / $yearNumber);

            // Assign the roman char * $matches
            $result .= str_repeat($roman, $matches);

            // Subtract from the number
            $year = $year % $yearNumber;
        }

        return $result;
    }
}

if (! function_exists('humanFilesize')) {
    /**
     * Show Human readable file size
     *
     *
     * @oaram int $precision
     */
    function humanFilesize(int $size, int $precision = 2): string
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $step = 1024;
        $i = 0;

        while (($size / $step) > 0.9) {
            $size = $size / $step;
            $i++;
        }

        return round($size, $precision).$units[$i];
    }
}

if (! function_exists('str_tease')) {
    /**
     * Shortens a string in a pretty way. It will clean it by trimming
     * it, remove all double spaces and html. If the string is then still
     * longer than the specified $length it will be shortened. The end
     * of the string is always a full word concatenated with the
     * specified moreTextIndicator.
     */
    function str_tease(string $string, int $length = 200, string $moreTextIndicator = '...'): string
    {
        $string = trim($string);

        //remove html
        $string = strip_tags($string);

        //replace multiple spaces
        $string = (string) preg_replace("/\s+/", ' ', $string);

        if (strlen($string) == 0) {
            return '';
        }

        if (strlen($string) <= $length) {
            return $string;
        }

        $ww = wordwrap($string, $length);

        return substr($ww, 0, (int) strpos($ww, "\n")).$moreTextIndicator;
    }
}

if (! function_exists('class_has_trait')) {
    /**
     * Check if a class has a specific trait
     * This can be sed when we create global traits that have scopes for example: draft
     */
    function class_has_trait(object|string $className, string $traitName): bool
    {
        if (is_object($className)) {
            $className = get_class($className);
        }

        return in_array($traitName, class_uses_recursive($className));
    }
}

if (! function_exists('checkDatabaseConnection')) {
    /**
     * Check if connection to DB is successfully
     */
    function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->reconnect();

            return true;
        } catch (Exception $ex) {
            report($ex);

            return false;
        }
    }
}

if (! function_exists('escapeSlashes')) {
    /**
     * Access the escapeSlashes helper.
     */
    function escapeSlashes(string $path): string
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('//', DIRECTORY_SEPARATOR, $path);

        return trim($path, DIRECTORY_SEPARATOR);
    }
}

if (! function_exists('validate')) {
    /**
     * Validate some data.
     * @param array<string, string>|string $fields
     * @param array<string, string>|string $rules
     */
    function validate(array|string $fields, array|string $rules): bool
    {
        if (! is_array($fields)) {
            $fields = ['default' => $fields];
        }

        if (! is_array($rules)) {
            $rules = ['default' => $rules];
        }

        return Validator::make($fields, $rules)->passes();
    }
}

if (! function_exists('isSSL')) {
    /**
     * Check if the site is using SSL
     */
    function isSSL(): bool
    {
        if (
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === 443) ||
            (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] === 443) ||
            (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https')
        ) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('number_format_short')) {
    function number_format_short(float $value, int $precision = 1): string
    {
        if (! is_numeric($value)) {
            return $value;
        }

        // 1 - 999 [$value > 0 && $value < 900]
        $n_format = number_format($value, $precision);
        $suffix = '';
        if ($value >= 900 && $value < 1000000) {
            // 0.9k-999k
            $n_format = number_format($value / 1000, $precision);
            $suffix = 'K';
        } elseif ($value >= 1000000 && $value < 1000000000) {
            // 1m-999m
            $n_format = number_format($value / 1000000, $precision);
            $suffix = 'M';
        } elseif ($value >= 1000000000 && $value < 1000000000000) {
            // 1b-999b
            $n_format = number_format($value / 1000000000, $precision);
            $suffix = 'B';
        } elseif ($value >= 1000000000000) {
            // 0.9t+
            $n_format = number_format($value / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove necessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotZero = '.'.str_repeat('0', $precision);
            $n_format = str_replace($dotZero, '', $n_format);
        }

        return ! empty($n_format.$suffix) ? $n_format.$suffix : 0;
    }
}

if (! function_exists('isTruthy')) {
    /**
     * Determine if a variable is Truthy or Falsy
     *
     * @param  bool  $checkTruthy - if we are checking for truthy or falsy value
     *
     * @example
     * isTruthy(true) => true
     * isTruthy(1) => true
     * isTruthy('1') => true
     * isTruthy('on') => true
     * isTruthy('yes') => true
     */
    function isTruthy(mixed $value, bool $checkTruthy = true): bool
    {
        $truthy = ['yes', 'on', '1', 'true', 1, true];

        $falsy = ['no', 'off', '0', 'false', 0, false];

        if ($checkTruthy) {
            return in_array($value, $truthy, true);
        } else {
            return in_array($value, $falsy, true);
        }
    }
}

if (! function_exists('isFalsy')) {
    /**
     * Determine if a variable is Falsy
     */
    function isFalsy(mixed $value): bool
    {
        return isTruthy($value, false);
    }
}

if (! function_exists('glob_recursive')) {
    /**
     * @return string[]
     */
    function glob_recursive(string $pattern, int $flags = 0): array
    {
        $files = glob($pattern, $flags);

        if (! $files) {
            $files = [];
        }

        $directories = glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT);

        if (! $directories) {
            $directories = [];
        }

        return array_reduce($directories, function (array $files, string $dir) use ($pattern, $flags): array {
            return array_merge(
                $files,
                glob_recursive($dir.'/'.basename($pattern), $flags)
            );
        }, $files);
    }
}

if (! function_exists('generateSerialNumber')) {
    /**
     * @example generateSerialNumber(1, 'RC', 'TR') // RC-TR-AAA-001
     * @example generateSerialNumber(1, 'RC', 'F') // RC-F-AAA-001
     */
    function generateSerialNumber(int $id, string $prefix = 'RC', ?string $type = 'TR'): string
    {
        $start = 703; // 0 = A, 703 = AAA, adjust accordingly
        $letters_value = $start + (ceil($id / 999) - 1);
        $numbers = ($id % 999 === 0) ? 999 : $id % 999;

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $letters = '';

        // while there are still positive integers to divide
        while ($letters_value) {
            $current = $letters_value % $base - 1; // We use -1 because we want to start at 0 index
            $letters = $characters[$current].$letters;
            $letters_value = floor($letters_value / $base);
        }

        if ($type == null) {
            return $prefix.'-'.$letters.'-'.sprintf('%03d', $numbers);
        }

        return $prefix.'-'.$type.'-'.$letters.'-'.sprintf('%03d', $numbers);
    }
}
