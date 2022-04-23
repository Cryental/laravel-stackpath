<?php

namespace Cryental\StackPath;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\Factory as HttpClient;
use UnexpectedValueException;

class StackPathProxies
{
    /**
     * The config repository instance.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The http factory instance.
     *
     * @var HttpClient
     */
    protected $http;

    /**
     * Create a new instance of CloudflareProxies.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @param \Illuminate\Http\Client\Factory         $http
     */
    public function __construct(Repository $config, HttpClient $http)
    {
        $this->config = $config;
        $this->http = $http;
    }

    /**
     * Retrieve Cloudflare proxies list.
     *
     * @return array
     */
    public function load(): array
    {
        return $this->retrieve();
    }

    /**
     * Retrieve requested proxy list by name.
     *
     * @return array
     */
    protected function retrieve(): array
    {
        try {
            $client_id = $this->config->get('laravelstackpath.client_id');
            $client_secret = $this->config->get('laravelstackpath.client_secret');

            $getBearerToken = $this->http->post('https://gateway.stackpath.com/identity/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            ])->throw();

            $response = $this->http->withHeaders([
                'Authorization' => 'Bearer ' . $getBearerToken->json()['access_token']
            ])->get('https://gateway.stackpath.com/cdn/v1/ips')->throw();
        } catch (\Exception $e) {
            throw new UnexpectedValueException('Failed to load trust proxies from StackPath server.', 1, $e);
        }

        return $response->json()['results'];
    }
}
