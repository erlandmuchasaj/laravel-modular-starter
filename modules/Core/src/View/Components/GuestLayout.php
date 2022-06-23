<?php

namespace Modules\Core\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        // dd('HERE');
        return view('core::layouts.guest');
    }
}
