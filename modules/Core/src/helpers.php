<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

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

if (!function_exists('locales')) {
    function locales(): Collection
    {
        return collect(config('app.locales', []));
    }
}

if (!function_exists('setAllLocale')) {
    /**
     * setAllLocale
     * Set application global locale
     *
     * @param string $locale
     */
    function setAllLocale(string $locale) : void
    {

        if (App::isLocale('en')) {

            // set laravel localization (lumen)
            app('translator')->setLocale($locale);

            // Set the Laravel locale
            App::setLocale($locale);

            // setLocale to use Carbon source locales. Enables diffForHumans() localized
            Carbon::setLocale($locale);

            // setLocale for php. Enables ->formatLocalized() with localized values for dates
            setlocale(LC_TIME, $locale);
            // setlocale(LC_TIME, config('app.locales')[$locale][1]);

            /*
             * Set the session variable for whether the app is using RTL support
             * For use in the blade directive in BladeServiceProvider
             */
            if (!app()->runningInConsole()) {
                if (config('app.locales')[$locale][2]) {
                    session(['lang-rtl' => true]);
                } else {
                    session()->forget('lang-rtl');
                }
            }
        }

    }
}

if (!function_exists('gravatar')) {
    /**
     * Gravatar URL
     * Generate a gravatar for a user
     *
     * @param  string $name
     * @return string
     */
    function gravatar(string $name): string
    {
        $gravatarId = md5(strtolower(trim($name)));
        return 'https://gravatar.com/avatar/' . $gravatarId . '?s=240';
    }
}

if (!function_exists('display_price')) {
    /**
     * display_price
     * convert price with decimals and separator
     *
     * @param int|string $price
     * @param int $decimals
     * @return string
     * @$price - Added hack in for when the variants are being created it passes over the new ISO currency code
     * which breaks number_format
     */
    function display_price(int|string $price, int $decimals = 2): string {
        if (!is_numeric($price)) {
            return $price;
        }

        $price =  preg_replace("/^([0-9]+\.?[0-9]*)(\s[A-Z]{3})$/", "$1", (string) $price);
        return number_format((float) $price, $decimals, '.', ',');
    }
}

if (!function_exists('diff_for_humans')) {
    function diff_for_humans(Carbon $date) : string
    {
        return $date->diffForHumans();
    }
}

if (!function_exists('convertToLocal')) {
    /**
     * convertToLocal
     * Used when displaying dates to frontend
     * **Display dates in user timezone on Frontend
     *
     * @param mixed|null $date
     * @param string $format
     *
     * @return string|null
     */
    function convertToLocal(mixed $date = null, string $format = 'D M j G:i:s T Y'): ?string
    {
        if (is_null($date)) {
            return $date;
        }

        $userTimezone = optional(auth()->user())->timezone ?? config('app.timezone');
        # $userTimezone = auth()->user()->timezone ?? config('app.timezone');

        if (!($date instanceof Carbon)) {
            if (is_numeric($date)) {
                # assuming is a timestamp 12547896857
                $date = Carbon::createFromTimestamp($date, config('app.timezone'));
            } else {
                $date = Carbon::parse($date, config('app.timezone'));
            }
        }

        return $date->setTimezone($userTimezone)->format($format);
    }
}

if (!function_exists('convertFromLocal')) {
    /**
     * convertFromLocal
     *
     * Saving the users input to the database in UTC
     * This will take a date/time, set it to the users' timezone then return it as UTC in a Carbon instance.
     *
     * @param mixed|null $date
     * @return Carbon|null
     */
    function convertFromLocal(mixed $date = null): ?Carbon
    {
        if (is_null($date)) {
            return $date;
        }

        $userTimezone = optional(auth()->user())->timezone ?? config('app.timezone');
        # $userTimezone = auth()->user()->timezone ?? config('app.timezone');

        if (!($date instanceof Carbon)) {
            if (is_numeric($date)) {
                # assuming is a timestamp 12547896857
                $date = Carbon::createFromTimestamp($date, $userTimezone);
            } else {
                $date = Carbon::parse($date, $userTimezone);
            }
        }

        return $date->setTimezone(config('app.timezone'));
    }
}

if (! function_exists('home_route')) {
    /**
     * Return the route to the "/" page depending on authentication/authorization status.
     *
     * @param string $default
     * @return string
     */
    function home_route(string $default = '/'): string
    {
//        if (auth()->check()) {
//            if (auth()->user()->isAdmin()) {
//                return config('app.route.backend_redirect');
//            }
//
//            if (auth()->user()->isUser()) {
//                return config('app.route.frontend_redirect');
//            }
//        }

        return $default;
    }
}

if (!function_exists('storage_asset')) {
    /**
     * Check if a storage file exists and return its URL.
     *
     * @param string|null $path
     * @param string|null $disk local|public|s3
     * @param string $type type of default image image|file|document|audio|video|avatar|not-available
     * @return string file path
     * @todo implement another method on file upload to increase performance
     */
    function storage_asset(string $path = null, string $disk = null, string $type = 'not-available'): string
    {
        $url = null;
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

if (!function_exists('getNWords')) {
    /**
     * Limit content with number of words
     *
     * @param string $string
     * @param int $n
     * @param bool $withDots
     *
     * @return string
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

if (!function_exists('getFacebookShareLink')) {
    /**
     * Get Facebook share link
     *
     * @param string $url
     * @param string $title
     *
     * @return string
     */
    function getFacebookShareLink(string $url, string $title): string
    {
        return 'https://www.facebook.com/sharer/sharer.php?u=' . $url .'&t=' . rawurlencode($title);
    }
}

if (!function_exists('getTwitterShareLink')) {
    /**
     * Get Twitter share link
     *
     * @param string $url
     * @param string $title
     *
     * @return string
     */
    function getTwitterShareLink(string $url, string $title): string
    {
        return 'https://twitter.com/intent/tweet?text=' . rawurlencode(implode(' ', [$title, $url]));
    }
}

if (!function_exists('roman_year')) {
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
            $matches = intval($year / $yearNumber);

            // Assign the roman char * $matches
            $result .= str_repeat($roman, $matches);

            // Subtract from the number
            $year = $year % $yearNumber;
        }

        return $result;
    }
}

if (!function_exists('humanFilesize')) {
    /**
     * Show Human readable file size
     * @param int $size
     * @param int $precision
     * @return string
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

if (!function_exists('str_tease')) {
    /**
     * Shortens a string in a pretty way. It will clean it by trimming
     * it, remove all double spaces and html. If the string is then still
     * longer than the specified $length it will be shortened. The end
     * of the string is always a full word concatenated with the
     * specified moreTextIndicator.
     *
     * @param string $string
     * @param int    $length
     * @param string $moreTextIndicator
     *
     * @return string
     */
    function str_tease(string $string, int $length = 200, string $moreTextIndicator = '...'): string
    {
        $string = trim($string);

        //remove html
        $string = strip_tags($string);

        //replace multiple spaces
        $string = strval(preg_replace("/\s+/", ' ', $string));

        if (strlen($string) == 0) {
            return '';
        }

        if (strlen($string) <= $length) {
            return $string;
        }

        $ww = wordwrap($string, $length, "\n");

        return substr($ww, 0, (int) strpos($ww, "\n")).$moreTextIndicator;
    }
}

if (!function_exists('class_has_trait')) {
    /**
     * Check if a class has a specific trait
     * This can be sed when we create global traits that have scopes for example: draft
     * @param object|string $className
     * @param string $traitName
     * @return bool
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
     * @return bool
     */
    function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->reconnect();

            return true;
        } catch (Exception $ex) {
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
     *
     * @param array|string $fields
     * @param array|string $rules
     *
     * @return bool
     */
    function validate(array|string $fields, array|string $rules): bool
    {
        if (!is_array($fields)) {
            $fields = ['default' => $fields];
        }

        if (!is_array($rules)) {
            $rules = ['default' => $rules];
        }

        return Validator::make($fields, $rules)->passes();
    }
}
