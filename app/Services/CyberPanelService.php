<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Exception;

class CyberPanelService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.cyberpanel.url'), '/');
        $this->token   = config('services.cyberpanel.token');
    }

    /**
     * Make a POST request to CyberPanel API
     *
     * @param string $endpoint
     * @param array $data
     * @return Response
     */
    protected function post(string $endpoint, array $data): Response
    {
        return Http::withToken($this->token)
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->post($endpoint, $data);
    }

    /**
     * Handle API response (success/error)
     *
     * @param Response $response
     * @param string $action
     * @param array $context
     * @return array
     */
    protected function handleResponse(Response $response, string $action, array $context = []): array
    {
        if ($response->successful()) {
            return [
                'success' => true,
                'data'    => $response->json(),
            ];
        }

        Log::error("CyberPanel {$action} failed", array_merge($context, [
            'response' => $response->body(),
        ]));

        return [
            'success' => false,
            'message' => $response->json('message') ?? "{$action} failed",
        ];
    }

    /**
     * Create a new website on CyberPanel
     *
     * @param string $domain
     * @param string $package
     * @param string $ownerEmail
     * @return array
     */
    public function createWebsite(string $domain, string $package = 'default', string $ownerEmail): array
    {
        $payload = [
            'domainName'  => $domain,
            'package'     => $package,
            'ownerEmail'  => $ownerEmail,
            'php'         => '8.2',
            'ssl'         => true,
        ];

        try {
            $response = $this->post('/api/createWebsite', $payload);
            return $this->handleResponse($response, 'createWebsite', ['domain' => $domain]);
        } catch (Exception $e) {
            Log::error("CyberPanel exception during createWebsite", [
                'domain' => $domain,
                'error'  => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Install WordPress on a given domain
     *
     * @param string $domain
     * @param string $title
     * @param string $adminUser
     * @param string $adminPass
     * @param string $adminEmail
     * @return array
     */
    public function installWordPress(string $domain, string $title, string $adminUser, string $adminPass, string $adminEmail): array
    {
        $payload = [
            'domainName' => $domain,
            'wpTitle'    => $title,
            'wpUser'     => $adminUser,
            'wpPass'     => $adminPass,
            'wpEmail'    => $adminEmail,
        ];

        try {
            $response = $this->post('/api/installWordPress', $payload);
            return $this->handleResponse($response, 'installWordPress', ['domain' => $domain]);
        } catch (Exception $e) {
            Log::error("CyberPanel exception during WordPress install", [
                'domain' => $domain,
                'error'  => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete an existing website
     *
     * @param string $domain
     * @return array
     */
    public function deleteWebsite(string $domain): array
    {
        $payload = [
            'domainName' => $domain,
        ];

        try {
            $response = $this->post('/api/deleteWebsite', $payload);
            return $this->handleResponse($response, 'deleteWebsite', ['domain' => $domain]);
        } catch (Exception $e) {
            Log::error("CyberPanel exception during deleteWebsite", [
                'domain' => $domain,
                'error'  => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
            ];
        }
    }
}
