<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        // resources/views/components/hpanel/home.blade.php রিটার্ন করছি
        return view('components.hpanel.home');
    }
}
