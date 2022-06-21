<?php

namespace Modules\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return View|string
     */
    public function render(): View|string
    {
        return view('core::layouts.guest');
    }
}
