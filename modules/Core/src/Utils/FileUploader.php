<?php

namespace Modules\Core\Utils;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Core\Exceptions\GeneralException;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FileUploader
{
    /**
     * File storage disk where files are stored
     *
     * @var string
     */
    private static string $disk = 'public';

    /**
     * constants used to build file path
     */
    const UPLOAD_PATH = 'uploads/{user_id}/{type}/{filename}';

    /**
     * The public visibility setting.
     *
     * @var string
     */
    const VISIBILITY_PUBLIC = 'public';

    /**
     * The private visibility setting.
     *
     * @var string
     */
    const VISIBILITY_PRIVATE = 'private';

    /**
     * Available file folder for sizes
     */
    const ORIGINAL = 'original'; // this is not a directory just used as name

    const THUMB = 'thumb';

    const XSMALL = 'xs';

    const SMALL = 'sm';

    const MEDIUM = 'md';

    const LARGE = 'lg';

    const XLARGE = 'xl';

    /**
     * Available file types
     * suppoerted by fileService
     */
    const IMAGE = 'image';

    const AUDIO = 'audio';

    const VIDEO = 'video';

    const FILE = 'file';

    const FONT = 'font';

    const ARCHIVE = 'archive';

    const DOCUMENT = 'document';

    const SPREADSHEETS = 'spreadsheets';

    /**
     * Available file types
     *
     * @var array<int, string>
     */
    public static array $validTypes = [
        self::IMAGE,
        self::AUDIO,
        self::VIDEO,
        self::FILE,
        self::FONT,
        self::ARCHIVE,
        self::DOCUMENT,
        self::SPREADSHEETS,
    ];

    /**
     * Available sizes for images
     *
     * @var array<string, int>
     */
    public static array $validSizes = [
        self::THUMB => 60,
        self::XSMALL => 150,
        self::SMALL => 300,
        self::MEDIUM => 768,
        self::LARGE => 1024,
        self::XLARGE => 2048,
    ];

    /**
     * validOptions of visibility
     *
     * @var array<int, string>
     */
    public static array $validOptions = [
        self::VISIBILITY_PUBLIC,
        self::VISIBILITY_PRIVATE,
    ];

    /**
     * $image_ext
     *
     * @var array<int, string>
     */
    private static array $image_ext = ['jpg', 'jpe', 'jpeg', 'png', 'gif', 'svg', 'svgz', 'tiff', 'tif', 'webp', 'ico'];

    /**
     * $font_ext
     *
     * @var array<int, string>
     */
    private static array $font_ext = ['ttc', 'otf', 'ttf', 'woff', 'woff2'];

    /**
     * $audio_ext
     *
     * @var array<int, string>
     */
    private static array $audio_ext = ['mp3', 'm4a', 'ogg', 'mpga', 'wav'];

    /**
     * $video_ext
     *
     * @var array<int, string>
     */
    private static array $video_ext = ['smv', 'movie', 'mov', 'wvx', 'wmx', 'wm', 'mp4', 'mp4', 'mp4v', 'mpg4', 'mpeg', 'mpg', 'mpe', 'wmv', 'avi', 'ogv', '3gp', '3g2'];

    /**
     * $document_ext
     *
     * @var array<int, string>
     */
    private static array $document_ext = ['css', 'csv', 'html', 'conf', 'log', 'txt', 'text', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx'];

    /**
     * $archive
     *
     * @var array<int, string>
     *
     * @example application/zip
     */
    private static array $archives_ext = ['gzip', 'rar', 'tar', 'zip', '7z'];

    /**
     * Create a new service provider instance.
     * @param mixed ...$args
     */
    public function __construct(...$args)
    {
        new FileUploader(...$args);
    }

    /**
     * Create e new instance of file uploader.
     *
     * @param ...$args
     * @return FileUploader
     */
    public static function make(...$args): FileUploader
    {
        return new FileUploader(...$args);
        // return new static(...$args);
    }

    /**
     * Upload a file into specified disk using
     * specified visibility and then store into DB.
     *
     * @param  UploadedFile  $file
     * @param  string|null  $disk
     * @param  array<string, string>  $args
     * @param  int|null  $user_id Who this file belongs to
     * @return array<string, string>
     *
     * @throws GeneralException
     */
    public static function store(
        UploadedFile $file,
        string $disk = null,
        array $args = [],
        int $user_id = null
    ): array
    {
        try {
            $data = self::upload($file, $disk, $args, $user_id);
        } catch (Exception $e) {
            throw new GeneralException($e->getMessage(), $e->getCode());
        }

        return $data;
    }

    /**
     * Upload an image to specific FileSystem
     *
     * @param  UploadedFile  $file
     * @param  string|null  $disk
     * @param  array<string, string> $args
     * @param  int|null  $user_id
     * @return array<string, string>
     *
     * @throws GeneralException
     */
    public static function upload(UploadedFile $file, string $disk = null, array $args = [], int $user_id = null): array
    {
        if (! isset($user_id)) {
            $user_id = auth()->id() ?? 1;
        }

        if ($file->getSize() === false) {
            abort(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, __('File failed to load.'));
        }

        try {
            // here you can put as many default values as you want.
            $defaults = [
                'alt' => '',
                'description' => '',
                'visibility' => self::VISIBILITY_PUBLIC, // public | private
            ];

            // merge default options with passed parameters
            $args = array_merge($defaults, $args);

            if ($disk === null) {
                $disk = self::$disk;
            }

            $disk = $disk ?: self::$disk;

            if (! in_array($args['visibility'], self::$validOptions)) {
                $args['visibility'] = self::VISIBILITY_PUBLIC;
            }

            // get filename with extension
            $filenameWithExtension = $file->getClientOriginalName();

            // get filename without extension
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);

            // get file extension
            $extension = $file->getClientOriginalExtension();

            //  Get the type of file we are storing
            $type = self::getType($extension);

            // filename to store
            $filenameToStore = Str::slug($filename).'_'.time().'.'.$extension;

            // Make a file path where image will be stored [uploads/{user_id}/{type}/{filename}]
            $filePath = self::getUserDir($filenameToStore, $type, $user_id);

            // Upload File to s3
            // $path = Storage::disk($disk)->put($filePath, file_get_contents($file), $args['visibility']); # save the file im memory
            // $path = Storage::disk($disk)->put($filePath, File::get($file), $args['visibility']); # save the file im memory
            $path = Storage::disk($disk)->put($filePath, fopen($file, 'r+'), $args['visibility']); // very nice for very big files

            // Store $filePath in the database
            if (! $path) {
                throw new GeneralException(__('File could not be uploaded to remote server!'));
            }

            $data = [
                'user_id' => $user_id,
                'type' => $type,
                'size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'dimensions' => self::getDimensions($file, $type),
                'name' => $filename,
                'disk' => $disk,
                'path' => $filePath,
                'alt' => $args['alt'] ?? null,
                'description' => $args['description'] ?? null,
                'visibility' => $args['visibility'], // indicate if file is public or private
                'url' => Storage::disk(self::$disk)->url($filePath),
                'original_name' => $file->getClientOriginalName(),
                'hash_file' => self::getHashFile($file),
            ];
        } catch (Exception $e) {
            throw new GeneralException($e->getMessage(), $e->getCode());
        }

        return $data;
    }

    /**
     * Create a streamed response for a given file.
     *
     * @param  string  $path
     * @return StreamedResponse Content
     */
    public static function get(string $path): StreamedResponse
    {
        return Storage::disk(self::$disk)->response($path);
    }

    /**
     * Create a streamed response for a given file.
     *
     * @param  string  $path
     * @return string Content
     *
     * @throws GeneralException
     */
    public static function getFile(string $path): string
    {
        if (! Storage::disk(self::$disk)->exists($path)) {
            throw new GeneralException(__('File [:file] does not exists.', ['file' => $path]));
        }

        return Storage::disk(self::$disk)->get($path);
    }

    /**
     * Get public path url.
     * Mainly used to access public files.
     *
     * @param  string  $path
     * @return string
     */
    public static function url(string $path): string
    {
        return Storage::disk(self::$disk)->url($path);
    }

    /**
     * Get file path.
     *
     * @param  string  $path
     * @return string
     */
    public static function path(string $path): string
    {
        return Storage::disk(self::$disk)->path($path);
    }

    /**
     * Download a specific resource
     *
     * @param  string  $path
     * @param  string|null  $name name with extensions.
     * @return StreamedResponse
     */
    public static function download(string $path, string|null $name): StreamedResponse
    {
        return Storage::disk(self::$disk)->download($path, $name);
    }

    /**
     * Get Visibility of a file
     *
     * @param  string  $path
     * @return string public|private
     */
    public static function getVisibility(string $path): string
    {
        return Storage::disk(self::$disk)->getVisibility($path);
    }

    /**
     * Set Visibility of a file
     *
     * @param string $path
     * @param string $visibility
     * @return bool
     */
    public static function setVisibility(string $path, string $visibility): bool
    {
        if (! in_array($visibility, self::$validOptions)) {
            return Storage::disk(self::$disk)->setVisibility($path, $visibility);
        }

        return false;
    }

    /**
     * Delete file from disk
     *
     * @param  string  $path
     * @param  bool  $throwError
     * @return  bool
     *
     * @throws GeneralException
     */
    public static function remove(string $path, bool $throwError = true): bool
    {
        if ($throwError && ! Storage::disk(self::$disk)->exists($path)) {
            throw new GeneralException(__('File does not exist!'));
        }

        return Storage::disk(self::$disk)->delete($path);
    }

    /**
     * @param $file
     * @return string
     */
    public static function getHashFile($file): string
    {
        return sha1_file($file->getRealPath());
    }

    /**
     * Create a streamed response for a given file.
     *
     * @param  string  $path
     * @return array<string, string>
     */
    public static function getMeta(string $path): array
    {
        $diskFrom = Storage::disk(self::$disk);

        //        $data = $diskFrom->getMetadata($path);
        //        $data['url'] = $diskFrom->url($path);
        //        $data['visibility'] = $diskFrom->getVisibility($path);
        //        $data['size_in_kb'] = self::formatBytes($data['size']);
        //        $data['last_modified'] = Carbon::createFromTimestamp($data['timestamp'])->diffForHumans();
        //        return $data;

         return [
             'path' => $diskFrom->path($path),
             'url' => $diskFrom->url($path),
             'visibility' => $diskFrom->getVisibility($path),
             'mimeType' => $diskFrom->mimeType($path),
                //             'getMetadata' => $diskFrom->getMetadata($path),
             'size' => self::formatBytes($diskFrom->size($path)),
             'last_modified' => Carbon::createFromTimestamp($diskFrom->lastModified($path))->diffForHumans(),
             'name' => basename($path),
             'pathinfo' => pathinfo($path),
         ];
    }

    /**
     * Get all extensions
     *
     * @return array<int, string> Extensions of all file types
     */
    public static function allExtensions(): array
    {
        return array_merge(self::$image_ext, self::$audio_ext, self::$video_ext, self::$document_ext, self::$archives_ext, self::$font_ext);
    }

    /**
     * Get type by extension
     *
     * @param  string  $ext Specific extension
     * @return string Type
     */
    private static function getType(string $ext): string
    {
        if (self::in_array($ext, self::$image_ext)) {
            return self::IMAGE;
        }

        if (self::in_array($ext, self::$audio_ext)) {
            return self::AUDIO;
        }

        if (self::in_array($ext, self::$video_ext)) {
            return self::VIDEO;
        }

        if (self::in_array($ext, self::$document_ext)) {
            return self::DOCUMENT;
        }

        if (in_array($ext, self::$font_ext)) {
            return self::FONT;
        }

        if (in_array($ext, self::$archives_ext)) {
            return self::ARCHIVE;
        }

        return self::FILE;
    }

    /**
     * Get directory for the specific user
     *
     * @param  string  $filename
     * @param  string  $type
     * @param  int|null  $user_id
     * @return string Specific user directory
     *
     * @example uploads/{user_id}/{type}/{filename}
     */
    private static function getUserDir(string $filename, string $type = self::FILE, int $user_id = null): string
    {
        if (! isset($user_id)) {
            $user_id = auth()->id() ?? 1; // default to superadmin
        }

        return trim(strtr(self::UPLOAD_PATH, [
            '{user_id}' => $user_id,
            '{type}' => $type,
            '{filename}' => $filename,
        ]), '/\\');
    }

    /**
     * Grab dimensions of an image.
     *
     * @param  UploadedFile  $file
     * @param  string  $type
     * @return string|null string|null
     */
    private static function getDimensions(UploadedFile $file, string $type = self::IMAGE): ?string
    {
        if ('image' !== $type) {
            return null;
        }

        if (self::isValidFileInstance($file) && $file->getClientMimeType() === 'image/svg+xml') {
            return null;
        }

        if (! self::isValidFileInstance($file) || ! $sizeDetails = @getimagesize($file->getRealPath())) {
            return null;
        }

        [$width, $height] = $sizeDetails;

        return $width.'x'.$height;
    }

    /**
     * Check that the given value is a valid file instance.
     *
     * @param  mixed  $file
     * @return bool
     */
    private static function isValidFileInstance(mixed $file): bool
    {
        if ($file instanceof SymfonyUploadedFile && ! $file->isValid()) {
            return false;
        }

        return $file instanceof SymfonyFile;
    }

    /**
     * @param $needle
     * @param $haystack
     * @return bool
     */
    private static function in_array($needle, $haystack): bool
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

    /**
     * get icon path
     *
     * @param  string  $mimeType
     * @return string
     */
    public static function getIconPath(string $mimeType): string
    {
        $file_type_icons_path = 'img'.DIRECTORY_SEPARATOR.'file-type-icons'.DIRECTORY_SEPARATOR;

        $icon_file = match ($mimeType) {
            'image/jpeg', 'image/pjpeg', 'image/x-jps' => 'jpeg.png',
            'image/png' => 'png.png',
            'image/gif' => 'gif.png',
            'image/bmp', 'image/x-windows-bmp' => 'bmp.png',
            'text/html', 'text/asp', 'text/javascript', 'text/ecmascript', 'application/x-javascript', 'application/javascript', 'application/ecmascript' => 'html.png',
            'text/plain' => 'conf.png',
            'text/css' => 'css.png',
            'audio/aiff', 'audio/x-aiff', 'audio/midi' => 'midi.png',
            'application/x-troff-msvideo', 'video/avi', 'video/msvideo', 'video/x-msvideo', 'video/avs-video' => 'avi.png',
            'video/animaflex' => 'fla.png',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-word.document.macroEnabled.12', 'application/vnd.ms-word.template.macroEnabled.12', 'application/vnd.oasis.opendocument.text', 'application/vnd.apple.pages', 'application/vnd.ms-xpsdocument', 'application/oxps', 'application/rtf', 'application/wordperfect', 'application/octet-stream' => 'docx.png',
            'application/x-compressed', 'application/x-7z-compressed', 'application/x-gzip', 'application/zip', 'multipart/x-gzip', 'multipart/x-zip' => 'zip.png',
            'application/x-gtar', 'application/rar', 'application/x-tar' => 'rar.png',
            'video/mpeg', 'audio/mpeg' => 'mpeg.png',
            'application/pdf' => 'pdf.png',
            'application/mspowerpoint', 'application/vnd.ms-powerpoint', 'application/powerpoint' => 'ms-pptx.png',
            'application/excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.apple.numbers', 'application/application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.ms-excel.sheet.macroEnabled.12', 'application/vnd.ms-excel.sheet.binary.macroEnabled.12', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'ms-xlsx.png',
            'image/vnd.adobe.photoshop' => 'psd.png',
            'not-found' => 'not-found.png',
            default => 'unknown.png',
        };

        return $file_type_icons_path.$icon_file;
    }

    /**
     * getFileType
     * Return file mimetype default: 'application/octet-stream'
     *
     * @param  string  $filename
     * @return bool|string
     */
    public static function getFileType(string $filename): bool|string
    {
        $mime_types = [

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms-office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        ];

        $arr = explode('.', $filename);
        $ext = strtolower(array_pop($arr));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $fileInfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($fileInfo, $filename);
            finfo_close($fileInfo);

            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

    /**
     * helper to format bytes to other units
     *
     * @param  int  $size in-bytes
     * @param  int  $precision
     * @return string
     */
    public static function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $bytes = max($size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

    /**
     * converts KB,MB,GB,TB,PB,EB,ZB,YB to bytes
     *
     * @param string $from
     * @return float|int|string
     *
     * @example 1KB => 1000 (bytes)
     */
    public static function convertToBytes(string $from): float|int|string
    {
        $number = (int) substr($from, 0, -2);

        return match (strtoupper(substr($from, -2))) {
            'KB' => $number * 1024,
            'MB' => $number * pow(1024, 2),
            'GB' => $number * pow(1024, 3),
            'TB' => $number * pow(1024, 4),
            'PB' => $number * pow(1024, 5),
            'EB' => $number * pow(1024, 6),
            'ZB' => $number * pow(1024, 7),
            'YB' => $number * pow(1024, 8),
            default => $from,
        };
    }

    /**
     * Convert bytes to the unit specified by the $to parameter.
     *
     * @param  int  $bytes  The filesize in Bytes.
     * @param  string  $to  The unit type to convert to. Accepts KB, MB, GB, TB or PB for Kilobytes, Megabytes, Gigabytes, Terabytes or PetaBytes, respectively.
     * @param  int  $decimal_places  The number of decimal places to return.
     * @return string Returns only the number of units, not the type letter. Returns 0 if the $to unit type is out of scope.
     *
     * @example 1024 (KB) => 1MB
     */
    public static function convertBytesToSpecified(int $bytes, string $to = 'MB', int $decimal_places = 2): string
    {
        $formulas = [
            'KB' => number_format($bytes / 1024, $decimal_places),
            'MB' => number_format($bytes / pow(1024, 2), $decimal_places),
            'GB' => number_format($bytes / pow(1024, 3), $decimal_places),
            'TB' => number_format($bytes / pow(1024, 4), $decimal_places),
            'PB' => number_format($bytes / pow(1024, 5), $decimal_places),
            'EB' => number_format($bytes / pow(1024, 6), $decimal_places),
            'ZB' => number_format($bytes / pow(1024, 7), $decimal_places),
            'YB' => number_format($bytes / pow(1024, 8), $decimal_places),
        ];

        return isset($formulas[$to]) ? $formulas[$to].$to : 0 .$to;
    }
}
