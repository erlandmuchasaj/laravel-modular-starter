<?php

namespace Modules\Core\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|string|Closure
    {
        return view('core::layouts.app');
    }
}
