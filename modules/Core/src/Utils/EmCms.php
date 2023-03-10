<?php

namespace Modules\Core\Utils;

/**
 * Base EMCMS starter class
 *
 * @note DO NOT EDIT or the hell will break loos.
 */
final class EmCms
{
    /**
     * The EmCms version.
     *
     * @var string
     */
    public const VERSION = '1.0.0';

    /**
     * The EmCms application name
     *
     * @var string
     */
    public const NAME = 'emcms';

    /**
     * The EmCms application author email
     *
     * @var string
     */
    public const AUTHOR = 'erland.muchasaj@gmail.com';

    /**
     * The default page limit
     *
     * @var int
     */
    public const PAGE_LIMIT = 15;

    /**
     * The default time format
     * This is used to show
     * to the user in FE
     *
     * @var string
     */
    public const DATE_TIME_FORMAT = 'd/m/Y H:i';
}
