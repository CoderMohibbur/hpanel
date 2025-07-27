<?php

namespace App\Jobs;

use App\Models\Hosting;
use App\Services\CyberPanelService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\HostingLog; // ← Add this at the top

use Exception;

class WordPressInstallJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $hosting;

    /**
     * Create a new job instance.
     */
    public function __construct(Hosting $hosting)
    {
        $this->hosting = $hosting;
    }

    /**
     * Execute the job.
     */
    public function handle(CyberPanelService $cyberPanel): void
    {
        try {
            // Optional: Check if already installed
            if ($this->hosting->wordpress_installed) {
                Log::info("WordPress already installed for: {$this->hosting->domain}");
                return;
            }

            // Generate admin credentials
            $adminUser  = 'admin';
            $adminPass  = Str::random(12);
            $adminEmail = 'admin@' . $this->hosting->domain;

            // Call CyberPanel API to install WordPress
            $response = $cyberPanel->installWordPress(
                $this->hosting->domain,
                $adminUser,
                $adminPass,
                $adminEmail,
                $this->hosting->template_id // Optional: for preloaded template
            );

                    // ✅ Log the action to hosting_logs
            HostingLog::create([
                'hosting_id' => $this->hosting->id,
                'action'     => 'wp_install',
                'response'   => json_encode($response),
            ]);

            // Mark as installed in DB
            $this->hosting->update([
                'wordpress_installed' => true,
                'wp_admin_user'       => $adminUser,
                'wp_admin_email'      => $adminEmail,
                'wp_admin_password'   => encrypt($adminPass), // encrypted for future use
            ]);

            Log::info("WordPress installation completed for: {$this->hosting->domain}");

        } catch (Exception $e) {
            Log::error("WordPress install failed for {$this->hosting->domain}: " . $e->getMessage());
            throw $e; // Optionally retry depending on queue settings
        }
    }
}