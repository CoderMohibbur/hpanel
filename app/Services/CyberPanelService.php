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
        // $this->baseUrl = rtrim(config('services.cyberpanel.url'), '/');  // https://bdixserver.host4speed.com:8090/cloudAPI
        // $this->token   = config('services.cyberpanel.token');

        $this->baseUrl = 'https://bdixserver.host4speed.com:8090/cloudAPI';  // https://bdixserver.host4speed.com:8090/cloudAPI
        $this->token   = 'Basic c8bf18bbb14b05e295978fd9a369ee3ef0e16702c0d3946d3aacdcc53d3208ae';
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
    public function createWebsite(string $domain, string $package = 'Default', string $ownerEmail): array
    {
        $payload = json_encode([
            "serverUserName" => "admin",
            "controller"     => "submitWebsiteCreation",
            "domainName"     => $domain,
            "package"        => $package,
            "adminEmail"     => $ownerEmail,
            "phpSelection"   => "PHP 7.4",
            "websiteOwner"   => "admin",
            "ssl"            => 0,
            "dkimCheck"      => 0,
            "openBasedir"    => 0
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://bdixserver.host4speed.com:8090/cloudAPI/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic c8bf18bbb14b05e295978fd9a369ee3ef0e16702c0d3946d3aacdcc53d3208ae',
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => false, // SSL Verify Disable
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $errorMsg = curl_error($curl);
            curl_close($curl);
            Log::error('cURL Error:', ['error' => $errorMsg]);
            return ['success' => false, 'message' => $errorMsg];
        }

        curl_close($curl);

        Log::debug('CyberPanel API Response:', ['body' => $response]);

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid JSON Response', ['response' => $response]);
            return ['success' => false, 'message' => 'Invalid JSON response'];
        }

        return [
            'success' => isset($decoded['status']) && $decoded['status'] == 1,
            'data'    => $decoded
        ];
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
            $response = $this->post('/', array_merge([
                'action' => 'installWordPress'
            ], $payload));
            return $this->handleResponse($response, 'installWordPress', ['domain' => $domain]);
        } catch (Exception $e) {
            Log::error("CyberPanel exception during installWordPress", [
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
        $payload = json_encode([
            "serverUserName" => "admin",
            "controller"     => "submitWebsiteDeletion",
            "websiteName"    => $domain,
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://bdixserver.host4speed.com:8090/cloudAPI/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic c8bf18bbb14b05e295978fd9a369ee3ef0e16702c0d3946d3aacdcc53d3208ae',
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => false, // SSL Verify Disable
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $errorMsg = curl_error($curl);
            curl_close($curl);
            Log::error('cURL Error during deleteWebsite:', ['error' => $errorMsg]);
            return ['success' => false, 'message' => $errorMsg];
        }

        curl_close($curl);

        Log::debug('CyberPanel DeleteWebsite API Response:', ['body' => $response]);

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Invalid JSON Response during deleteWebsite', ['response' => $response]);
            return ['success' => false, 'message' => 'Invalid JSON response'];
        }

        return [
            'success' => isset($decoded['status']) && $decoded['status'] == 1,
            'data'    => $decoded
        ];
    }
}
