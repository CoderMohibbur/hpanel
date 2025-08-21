<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HorizonController extends Controller
{
    public function index()
    {
        // যদি দরকার হয় পরে ডাইনামিক ডেটা পাঠাতে পারবেন
        $faqs = [
            [
                'q' => 'What is Hostinger Horizons, and how does it work?',
                'a' => 'Describe your idea in a chat and Horizons scaffolds a working site/app with deployment in one click—no code required. You can keep editing from the UI.',
            ],
            [
                'q' => 'What can I build with Hostinger Horizons?',
                'a' => 'Marketing sites, product pages, blogs, dashboards, and simple web apps. It ships responsive Tailwind UI blocks and integrates with your stack.',
            ],
            [
                'q' => 'How much does it cost to use Hostinger Horizons?',
                'a' => 'You can start free; paid tiers unlock custom domains, collaboration, and advanced components. Pricing varies by region.',
            ],
        ];

        return view('components.hpanel.horizons', compact('faqs'));
    }
}
