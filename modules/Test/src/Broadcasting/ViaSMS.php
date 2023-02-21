<?php

namespace Modules\Test\Broadcasting;

use Modules\User\Models\User\User;

class ViaSMS
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @return void
     */
    public function join(Modules\User\Models\User\User $user): void
    {
        //
    }
}
