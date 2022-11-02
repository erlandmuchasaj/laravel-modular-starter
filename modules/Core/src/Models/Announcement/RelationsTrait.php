<?php

namespace Modules\Core\Models\Announcement;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User\User;

trait RelationsTrait
{
    /**
     * Get the user that created the announcement.
     *
     * @return BelongsTo<User, Announcement>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
