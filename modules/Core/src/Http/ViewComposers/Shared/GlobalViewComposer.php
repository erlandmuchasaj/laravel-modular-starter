<?php

namespace Modules\Core\Http\ViewComposers\Shared;

use Illuminate\Contracts\View\View;

class GlobalViewComposer
{
    public function compose(View $view): void
    {
        $currentUser = auth()->user();

        $view->with(compact('currentUser'));
    }
}
