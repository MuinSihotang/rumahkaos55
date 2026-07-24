<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        // Pastikan ini mengarah ke view layouts.app
        return view('layouts.app'); 
    }
}