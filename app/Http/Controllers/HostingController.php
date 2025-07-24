<?php

namespace App\Http\Controllers;

use App\Models\Hosting;
use App\Services\CyberPanelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostingController extends Controller
{
    /**
     * Show all hostings for the logged-in user
     */
    public function index()
    {
        $hostings = Hosting::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('hosting.index', compact('hostings'));
    }

    /**
     * Show the hosting creation form
     */
    public function create()
    {
        // Static packages and plans (optional: fetch from DB)
        $packages = ['default', 'basic', 'pro']; // CyberPanel packages
        $plans = ['Free', 'Pro']; // SaaS plans

        return view('hosting.create', compact('packages', 'plans'));
    }

    /**
     * Store new hosting and call CyberPanel API
     */
    public function store(Request $request, CyberPanelService $cyber)
    {
        $request->validate([
            'domain' => 'required|unique:hostings,domain',
            'package' => 'required|string',
            'plan' => 'required|string|in:Free,Pro',
        ]);

        $response = $cyber->createWebsite($request->domain, $request->package, $request->plan);
        $isSuccess = $response['success'] ?? false;

        Hosting::create([
            'user_id' => Auth::id(),
            'domain' => $request->domain,
            'package' => $request->package,
            'plan' => $request->plan,
            'cyberpanel_status' => $isSuccess ? 'success' : 'failed',
            'cyberpanel_message' => $response['message'] ?? 'API error',
            'ssl' => true,
            'expiry_date' => now()->addDays(30),
        ]);

        return redirect()
            ->route('hosting.index')
            ->with('status', $isSuccess ? 'Hosting provisioned successfully!' : 'Hosting provision failed.');
    }
}
