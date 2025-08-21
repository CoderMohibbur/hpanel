<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VpsController extends Controller
{
    /**
     * Show the VPS page.
     */
    public function index()
    {
        return view('components.hpanel.vps');
    }

    /**
     * Show KVM VPS details.
     */
    public function kvm()
    {
        return view('components.hpanel.vps-kvm'); 
        // 👉 চাইলে আলাদা blade বানাবে
    }

    /**
     * Show Game Panel VPS details.
     */
    public function gamePanel()
    {
        return view('components.hpanel.vps-game'); 
        // 👉 চাইলে আলাদা blade বানাবে
    }
}
