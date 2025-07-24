<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class CyberPanelService
{
    protected string $apiUrl;
    protected string $apiToken;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.cyberpanel.url'), '/');
        $this->apiToken = config('services.cyberpanel.token');
    }

    /**
     * Make POST request to CyberPanel API
     */
    protected function post(string $endpoint, array $data): Response
    {
        return Http::withToken($this->apiToken)
            ->baseUrl($this->apiUrl)
            ->acceptJson()
            ->post($endpoint, $data);
    }

    /**
     * Create a new website on CyberPanel
     */
    public function createWebsite(string $domain, string $package = 'default', string $ownerEmail): array
    {
        $payload = [
            'domainName' => $domain,
            'package' => $package,
            'ownerEmail' => $ownerEmail,
            // 'ownerEmail' => $ownerEmail ?? auth()->user()->email,
            'php' => '8.2',
            'ssl' => true,
        ];

        $response = $this->post('/api/createWebsite', $payload);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        // Log error for debugging
        Log::error('CyberPanel createWebsite failed', [
            'domain' => $domain,
            'response' => $response->body(),
        ]);

        return [
            'success' => false,
            'message' => $response->json('message') ?? 'Unknown error',
        ];
    }

    /**
     * Install WordPress on a domain
     */
    public function installWordPress(string $domain, string $title, string $adminUser, string $adminPass, string $adminEmail): array
    {
        $payload = [
            'domainName' => $domain,
            'wpTitle' => $title,
            'wpUser' => $adminUser,
            'wpPass' => $adminPass,
            'wpEmail' => $adminEmail,
        ];

        $response = $this->post('/api/installWordPress', $payload);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        Log::error('CyberPanel WordPress install failed', [
            'domain' => $domain,
            'response' => $response->body(),
        ]);

        return [
            'success' => false,
            'message' => $response->json('message') ?? 'WordPress install failed',
        ];
    }

    /**
     * Delete an existing website (Optional)
     */
    public function deleteWebsite(string $domain): array
    {
        $payload = [
            'domainName' => $domain,
        ];

        $response = $this->post('/api/deleteWebsite', $payload);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        Log::error('CyberPanel deleteWebsite failed', [
            'domain' => $domain,
            'response' => $response->body(),
        ]);

        return [
            'success' => false,
            'message' => $response->json('message') ?? 'Delete failed',
        ];
    }
}