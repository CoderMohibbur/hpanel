<?php

namespace App\Http\Controllers;

use App\Models\Hosting;
use App\Models\HostingLog;
use App\Services\CyberPanelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HostingController extends Controller
{
    use AuthorizesRequests;
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
            'domain'  => 'required|unique:hostings,domain',
            'package' => 'required|string',
            'plan'    => 'required|string|in:Free,Pro',
        ]);

        $response = $cyber->createWebsite($request->domain, $request->package, Auth::user()->email);

        $status  = $response['data']['status'] ?? 0;
        $message = $response['data']['error_message'] ?? 'Unknown error';

        if ($status == 1) {
            // Create Hosting
            $hosting = Hosting::create([
                'user_id'            => Auth::id(),
                'domain'             => $request->domain,
                'package'            => $request->package,
                'plan'               => $request->plan,
                'cyberpanel_status'  => 'success',
                'cyberpanel_message' => $message,
                'ssl'                => true,
                'expiry_date'        => now()->addDays(30),
            ]);

            // Create Hosting Log
            HostingLog::create([
                'hosting_id' => $hosting->id,
                'action'     => 'createWebsite',
                'response'   => json_encode($response),
            ]);

            return redirect()
                ->route('hosting.index')
                ->with('status', 'Hosting provisioned successfully!');
        }

        // API failed → Nothing will be created.
        return redirect()
            ->route('hosting.index')
            ->with('error', $message ?? 'Hosting provision failed.');
    }




    /**
     * Show edit form for updating package/plan
     */
    public function edit(Hosting $hosting)
    {
        // $this->authorize('update', $hosting);

        $packages = ['default', 'basic', 'pro'];
        $plans = ['Free', 'Pro'];

        return view('hosting.edit', compact('hosting', 'packages', 'plans'));
    }

    /**
     * Update hosting package/plan (local only)
     */
    public function update(Request $request, Hosting $hosting)
    {
        // $this->authorize('update', $hosting);

        $request->validate([
            'package' => 'required|string',
            'plan'    => 'required|string|in:Free,Pro',
        ]);

        $hosting->update([
            'package' => $request->package,
            'plan'    => $request->plan,
        ]);

        return redirect()
            ->route('hosting.index')
            ->with('status', 'Hosting details updated successfully!');
    }

    /**
     * Delete hosting from CyberPanel and local DB
     */
    public function destroy(Hosting $hosting, CyberPanelService $cyber)
    {
        // $this->authorize('delete', $hosting);

        $response = $cyber->deleteWebsite($hosting->domain);
        $isSuccess = $response['success'] ?? false;

        if ($isSuccess) {
            // Delete Hosting Entry from DB
            $hosting->delete();

            // Log Deletion in Hosting Logs
            HostingLog::create([
                'hosting_id' => $hosting->id,
                'action'     => 'deleteWebsite',
                'response'   => json_encode($response['data'] ?? $response),
            ]);

            return redirect()
                ->route('hosting.index')
                ->with('status', 'Hosting deleted successfully!');
        }

        // API Failed → Show Error Message
        $errorMessage = $response['data']['error_message'] ?? $response['message'] ?? 'Failed to delete hosting on CyberPanel.';

        return redirect()
            ->route('hosting.index')
            ->with('error', $errorMessage);
    }


    /**
     * Refresh CyberPanel Status (optional)
     */
    public function refreshStatus(Hosting $hosting)
    {
        // $this->authorize('update', $hosting);

        // Placeholder for API call to fetch updated status if needed.
        // Example: $response = $cyber->getWebsiteStatus($hosting->domain);

        $hosting->cyberpanel_status = 'active'; // simulate update
        $hosting->save();

        return redirect()->back()->with('status', 'Hosting status refreshed.');
    }
}
