<?php

namespace Modules\Core\Models\Announcement;

use Parsedown;

trait AccessorTrait
{

    /**
     * Get the parsed body of the announcement.
     *
     * @return string
     */
    public function getParsedBodyAttribute(): string
    {
        return (new Parsedown)->text(htmlspecialchars($this->attributes['message']));
    }

}
