<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TenantLayout extends Component
{
    /**
     * Tenant pages use the same Breeze app layout - it shows tenant sidebar when on tenant routes.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
