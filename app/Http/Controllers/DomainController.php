<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DomainController extends Controller
{
    // 1) Portfolio (empty state / list)
    public function index()
    {
        // TODO: বাস্তবে এখানে ইউজারের ডোমেইনগুলো আনবেন
        $domains = []; // উদাহরণ: [['name'=>'myco.com','expiry'=>'2026-01-01'], ...]
        return view('components.hpanel.domains.portfolio', compact('domains'));
    }

    // 2) Get a new domain (search + ফলাফল)
    public function register(Request $request)
    {
        $q = trim($request->query('q', ''));
        $tlds = ['.com', '.net', '.org', '.dev', '.io'];

        $results = [];
        if ($q !== '') {
            $base = strtolower(preg_replace('/[^a-z0-9-]/', '', $q));
            $priceMap = ['.com'=>12.99, '.net'=>11.99, '.org'=>10.99, '.dev'=>14.99, '.io'=>39.99];

            foreach ($tlds as $tld) {
                $domain = $base . $tld;
                // ডেমো অ্যাভেইলেবিলিটি (deterministic pseudo):
                $available = (crc32($domain) % 3) !== 0;
                $price = $priceMap[$tld];
                $results[] = compact('domain', 'available', 'price');
            }
        }

        return view('components.hpanel.domains.register', compact('q', 'results'));
    }

    // 3) Transfer domain (eligibility + form)
    public function transfer(Request $request)
    {
        $domain   = trim($request->query('domain', ''));
        $eligible = null;

        if ($domain !== '') {
            // ডেমো eligibility: ডট আছে এবং সাবডোমেইন নয়
            $eligible = substr_count($domain, '.') >= 1 && !str_starts_with($domain, '.');
        }

        return view('components.hpanel.domains.transfer', compact('domain', 'eligible'));
    }
}