<?php

namespace Modules\Core\Models\ActivityLog;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User\User;

trait RelationsTrait
{
    /**
     * Get the user that created the announcement.
     *
     * @return BelongsTo<User, ActivityLog>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id', 'id')
            ->where('causer_type', (new User)->getMorphClass());
    }
}
