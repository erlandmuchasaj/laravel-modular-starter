<?php

namespace Modules\Core\Traits;

use Modules\Core\Utils\EmCms;

trait NamespacedEntity
{


    /**
     * The root namespace to assume when generating URLs to actions.
     * @var string|null
     */
    protected static ?string $entityNamespace = null;

    /**
     * Returns the entity namespace.
     *
     * @return string
     */
    public static function getEntityNamespace(): string
    {
        return static::$entityNamespace ?? get_called_class();
    }

    /**
     * Sets the entity namespace.
     *
     * @param string $namespace
     * @return void
     */
    public static function setEntityNamespace(string $namespace): void
    {
        static::$entityNamespace = $namespace;
    }
}
