<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HostingController extends Controller
{
    public function index()
    {
        // view: resources/views/components/hpanel/hosting.blade.php
        return view('components.hpanel.hosting');
    }
}
