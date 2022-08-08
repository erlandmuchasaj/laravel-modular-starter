<?php

namespace Modules\Core\Utils;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Core\Exceptions\GeneralException;
use Modules\User\Models\User\User;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;
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
     * constants used to build file image path
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
    const THUMB  = 'thumb';
    const XSMALL = 'xs';
    const SMALL  = 'sm';
    const MEDIUM = 'md';
    const LARGE  = 'lg';
    const XLARGE = 'xl';

    /**
     * Available file types
     * suppoerted by fileService
     */
    const IMAGE    = 'image';
    const AUDIO    = 'audio';
    const VIDEO    = 'video';
    const FILE     = 'file';
    const FONT     = 'font';
    const ARCHIVE  = 'archive';
    const DOCUMENT = 'document';
    const SPREADSHEETS = 'spreadsheets';

    /**
     * Available file types
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
     * @var array<int, string>
     */
    private static array $image_ext    = ['jpg', 'jpe', 'jpeg', 'png', 'gif', 'svg', 'svgz', 'tiff', 'tif', 'webp', 'ico'];

    /**
     * $font_ext
     * @var array<int, string>
     */
    private static array $font_ext = ['ttc', 'otf', 'ttf', 'woff', 'woff2'];

    /**
     * $audio_ext
     * @var array<int, string>
     */
    private static array $audio_ext = ['mp3', 'm4a', 'ogg', 'mpga', 'wav'];

    /**
     * $video_ext
     * @var array<int, string>
     */
    private static array $video_ext = ['smv', 'movie', 'mov', 'wvx', 'wmx', 'wm', 'mp4', 'mp4', 'mp4v', 'mpg4', 'mpeg', 'mpg', 'mpe', 'wmv', 'avi', 'ogv', '3gp', '3g2'];

    /**
     * $document_ext
     * @var array<int, string>
     */
    private static array $document_ext = ['css', 'csv', 'html', 'conf', 'log', 'txt', 'text', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx'];

    /**
     * $archive
     * @var array<int, string>
     * @example application/zip
     */
    private static array $archives_ext = ['gzip', 'rar', 'tar', 'zip', '7z'];

    /**
     * Upload a file into specified disk using
     * specified visibility and then store into DB.
     *
     * @param UploadedFile $file
     * @param string|null $disk
     * @param array $args
     * @param int $user_id Who this file belongs to
     *
     * @return array File
     * @throws GeneralException
     */
    public static function store(UploadedFile $file, string $disk = null, array $args = [], $user_id = null): array
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
     * @param UploadedFile $file
     * @param string|null $disk
     * @param array $args
     * @param null $user_id
     * @return array
     * @throws GeneralException
     */
    public static function upload(UploadedFile $file, string $disk = null, array $args = [], $user_id = null): array
    {

        if (is_null($user_id)) {
            $user_id = auth()->id() ?? 1;
        }

        if ($file->getSize() === false) {
            abort(415, __('File failed to load.'));
        }


        try {

            # here you can put as many default values as you want.
            $defaults = [
                'folder'      => '',
                'alt'         => '',
                'description' => '',
                'visibility'  => self::VISIBILITY_PRIVATE, # public | private
            ];

            # merge default options with passed parameters
            $args = array_merge($defaults, $args);

            if ($disk === null) {
                $disk = config('filesystems.default');
            }

            $folder = '';
            if (!empty($args['folder']) && is_string($args['folder'])) {
                $folder = rtrim($args['folder'], '/\\') . '/';
            }

            if (!in_array($args['visibility'], self::$validOptions)) {
                $args['visibility'] = self::VISIBILITY_PRIVATE;
            }

            # get filename with extension
            $filenameWithExtension = $file->getClientOriginalName();

            # get filename without extension
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);

            # get file extension
            $extension = $file->getClientOriginalExtension();

            #  Get the type of file we are storing
            $type = self::getType($extension);

            # filename to store
            $filenameToStore = Str::slug($filename).'_'.time().'.'.$extension;

            # Make a file path where image will be stored [uploads/{user_id}/{type}/{filename}]
            $filePath = self::getUserDir($filenameToStore, $type, $user_id);

            # Upload File to s3
            // $path = Storage::disk($disk)->put($filePath, file_get_contents($file), $args['visibility']); # save the file im memory
            // $path = Storage::disk($disk)->put($filePath, File::get($file), $args['visibility']); # save the file im memory
            $path = Storage::disk($disk)->put($filePath, fopen($file, 'r+'), $args['visibility']); // very nice for very big files

            # Store $filePath in the database
            if (!$path) {
                throw new GeneralException('File could not be uploaded to remote server!');
            }

            $data = [
                'user_id'   => $user_id,
                'type'      => $type, # was: $file->getType(),
                'size'      => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'dimensions'=> self::getDimensions($file, $type),
                'name'      => $filename,
                'disk'      => $disk,
                'path'      => $filePath, # was: $path
                'alt'         => $args['alt'] ?? null,
                'description' => $args['description'] ?? null,
                'visibility'  => $args['visibility'], # indicate if file is public or private

                # !! extras
                'url'       => Storage::disk(self::$disk)->url($filePath),
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
     *
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
     *
     * @return string Content
     * @throws GeneralException
     */
    public static function getFile(string $path): string
    {
        if (!Storage::disk(self::$disk)->exists($path)) {
            throw new GeneralException(__('File [:file] does not exists.', ['file' => $path]));
        }

        return Storage::disk(self::$disk)->get($path);
    }

    /**
     * Get public path url.
     * Mainly used to access public files.
     *
     * @param  string  $path
     *
     * @return string
     */
    public static function url(string $path): string
    {
        return Storage::disk(self::$disk)->url($path);
    }

    /**
     * Get file path.
     *
     * @param string $path
     * @return string
     */
    public static function path(string $path): string
    {
        return Storage::disk(self::$disk)->path($path);
    }

    /**
     * Download a specific resource
     *
     * @param string $path
     * @param  string|null  $name name with extensions.
     *
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
     *
     * @return string public|private
     */
    public static function getVisibility(string $path): string
    {
        return Storage::disk(self::$disk)->getVisibility($path);
    }

    /**
     * Set Visibility of a file
     *
     * @param  string  $path
     * @param  string  $visibility
     *
     * @return bool
     */
    public static function setVisibility(string $path, string $visibility): bool
    {
        if (!in_array($visibility, self::$validOptions)) {
            return Storage::disk(self::$disk)->setVisibility($path, $visibility);
        }

        return false;
    }

    /**
     * Delete file from disk
     *
     * @param string $path
     * @return  bool
     * @throws Exception
     */
    public static function remove(string $path): bool
    {
        if (!Storage::disk(self::$disk)->exists($path)) {
            throw new GeneralException('File does not exist!');
        }

        return Storage::disk(self::$disk)->delete($path);
    }

    /**
     * @param $file
     *
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
     *
     * @return array Content
     */
    public static function getMeta(string $path): array
    {

        $diskFrom = Storage::disk(self::$disk);

        $data = $diskFrom->getMetadata($path);

        $data['url'] = $diskFrom->url($path);
        $data['visibility'] = $diskFrom->getVisibility($path);
        $data['size_in_kb'] = self::formatBytes($data['size'] ?? 0);
        $data['last_modified'] = Carbon::createFromTimestamp($data['timestamp'])->diffForHumans();

        return $data;

        // return [
        //     'path' => $diskFrom->path($path),
        //     'url' => $diskFrom->url($path),
        //     'visibility' => $diskFrom->getVisibility($path),
        //     'mimeType' => $diskFrom->mimeType($path),
        //     'getMetadata' => $diskFrom->getMetadata($path),
        //     'size' => self::formatBytes($diskFrom->size($path)),
        //     'last_modified' => Carbon::createFromTimestamp($diskFrom->lastModified($path))->diffForHumans(),
        //     'name' => basename($path),
        //     'pathinfo' => pathinfo($path),
        // ];
    }

    /**
     * Get all extensions
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
     *
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
     *
     * @return string Specific user directory
     *
     * @example uploads/{user_id}/{type}/{filename}
     */
    private static function getUserDir(string $filename, string $type = self::FILE, int $user_id = null): string
    {
        if (!isset($user_id)){
            $user_id = auth()->id() ?? 1; // default to superadmin
        }

        return trim(strtr(self::UPLOAD_PATH, [
            '{user_id}' => $user_id,
            '{type}' => $type,
            '{filename}' => $filename
        ]), '/\\');
    }

    /**
     * Grab dimensions of an image.
     *
     * @param  UploadedFile $file
     * @param  string  $type
     *
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

        return ($width . 'x' . $height);
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
     *
     * @return string
     */
    public static function getIconPath(string $mimeType): string
    {
        $file_type_icons_path = 'img/file-type-icons/';

        switch($mimeType)
        {
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'image/x-jps':
                $icon_file = 'jpeg.png';
                break;

            case 'image/png':
                $icon_file = 'png.png';
                break;

            case 'image/gif':
                $icon_file = 'gif.png';
                break;

            case 'image/bmp':
            case 'image/x-windows-bmp':
                $icon_file = 'bmp.png';
                break;

            case 'text/html':
            case 'text/asp':
            case 'text/javascript':
            case 'text/ecmascript':
            case 'application/x-javascript':
            case 'application/javascript':
            case 'application/ecmascript':
                $icon_file = 'html.png';
                break;

            case 'text/plain':
                $icon_file = 'conf.png';
                break;

            case 'text/css':
                $icon_file = 'css.png';
                break;

            case 'audio/aiff':
            case 'audio/x-aiff':
            case 'audio/midi':
                $icon_file = 'midi.png';
                break;

            case 'application/x-troff-msvideo':
            case 'video/avi':
            case 'video/msvideo':
            case 'video/x-msvideo':
            case 'video/avs-video':
                $icon_file = 'avi.png';
                break;

            case 'video/animaflex':
                $icon_file = 'fla.png';
                break;

            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/vnd.ms-word.document.macroEnabled.12':
            case 'application/vnd.ms-word.template.macroEnabled.12':
            case 'application/vnd.oasis.opendocument.text':
            case 'application/vnd.apple.pages':
            case 'application/vnd.ms-xpsdocument':
            case 'application/oxps':
            case 'application/rtf':
            case 'application/wordperfect':
            case 'application/octet-stream':
                $icon_file = 'docx.png';
                break;

            case 'application/x-compressed':
            case 'application/x-7z-compressed':
            case 'application/x-gzip':
            case 'application/zip':
            case 'multipart/x-gzip':
            case 'multipart/x-zip':
                $icon_file = 'zip.png';
                break;

            case 'application/x-gtar':
            case 'application/rar':
            case 'application/x-tar':
                $icon_file = 'rar.png';
                break;

            case 'video/mpeg':
            case 'audio/mpeg':
                $icon_file = 'mpeg.png';
                break;

            case 'application/pdf':
                $icon_file = 'pdf.png';
                break;

            case 'application/mspowerpoint':
            case 'application/vnd.ms-powerpoint':
            case 'application/powerpoint':
                $icon_file = 'ms-pptx.png';
                break;


            case 'application/excel':
            case 'application/x-excel':
            case 'application/x-msexcel':
            case 'application/vnd.apple.numbers':
            case 'application/application/vnd.oasis.opendocument.spreadsheet':
            case 'application/vnd.ms-excel.sheet.macroEnabled.12':
            case 'application/vnd.ms-excel.sheet.binary.macroEnabled.12':
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $icon_file = 'ms-xlsx.png';
                break;

            case 'image/vnd.adobe.photoshop':
                $icon_file = 'psd.png';
                break;

            case 'not-found':
                $icon_file = 'not-found.png';
                break;

            default:
                $icon_file = 'unknown.png';
                break;
        }
        return $file_type_icons_path.$icon_file;
    }


    /**
     * getFileType
     * Return file mimetype default: 'application/octet-stream'
     *
     *
     * @param  string  $filename
     *
     * @return bool|string
     */
    public static function getFileType(string $filename): bool|string
    {
        $mime_types = array(

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
        );

        $arr = explode('.',$filename);
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
     *
     * @return string
     */
    public static function formatBytes(int $size, int $precision = 2): string
    {
        $units =  ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $bytes = max($size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * converts KB,MB,GB,TB,PB,EB,ZB,YB to bytes
     *
     * @example 1KB => 1000 (bytes)
     *
     * @param $from
     *
     * @return float|int|string
     */
    public static function convertToBytes($from): float|int|string
    {
        $number = (float) substr($from,0,-2);
        return match (strtoupper(substr($from, -2))) {
            "KB" => $number * 1024,
            "MB" => $number * pow(1024, 2),
            "GB" => $number * pow(1024, 3),
            "TB" => $number * pow(1024, 4),
            "PB" => $number * pow(1024, 5),
            "EB" => $number * pow(1024, 6),
            "ZB" => $number * pow(1024, 7),
            "YB" => $number * pow(1024, 8),
            default => $from,
        };
    }

    /**
     * Convert bytes to the unit specified by the $to parameter.
     *
     * @param  integer $bytes  The filesize in Bytes.
     * @param  string  $to  The unit type to convert to. Accepts KB, MB, GB, TB or PB for Kilobytes, Megabytes, Gigabytes, Terabytes or PetaBytes, respectively.
     * @param  integer $decimal_places  The number of decimal places to return.
     *
     * @return string Returns only the number of units, not the type letter. Returns 0 if the $to unit type is out of scope.
     * @example 1000 (KB) => 1
     *
     */
    public static function convertBytesToSpecified(int $bytes, string $to, int $decimal_places = 2): string
    {
        $formulas = array(
            'KB' => number_format($bytes / 1024, $decimal_places),
            'MB' => number_format($bytes / 1048576, $decimal_places),
            'GB' => number_format($bytes / 1073741824, $decimal_places),
            'TB' => number_format($bytes / 1099511627776, $decimal_places),
            'PB' => number_format($bytes / 1125899906842624, $decimal_places),
        );
        return isset($formulas[$to]) ? $formulas[$to] . $to : 0 . $to;
    }

}
