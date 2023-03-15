<?php

namespace Modules\Core\Traits;

trait NamespacedEntity
{
    /**
     * The root namespace to assume when generating URLs to actions.
     */
    protected static ?string $entityNamespace = null;

    /**
     * Returns the entity namespace.
     */
    public static function getEntityNamespace(): string
    {
        return static::$entityNamespace ?? get_called_class();
    }

    /**
     * Sets the entity namespace.
     */
    public static function setEntityNamespace(string $namespace): void
    {
        static::$entityNamespace = $namespace;
    }
}
