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
        // return view('core::layouts.guest');
        return view('core::components.guest-layout');
    }
}
