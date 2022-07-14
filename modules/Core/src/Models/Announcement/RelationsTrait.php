<?php

namespace Modules\Core\Models\Announcement;

trait RelationsTrait
{

    /**
     * Get the user that created the announcement.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('Modules\User\Models\User\User', 'user_id');
    }

}
