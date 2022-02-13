<?php

namespace Cryental\StackPath;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Str;
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
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Http\Client\Factory  $http
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
            $url = $this->config->get('laravelstackpath.url');

            $response = $this->http->get($url)->throw();
        } catch (\Exception $e) {
            throw new UnexpectedValueException('Failed to load trust proxies from StackPath server.', 1, $e);
        }

        return array_filter(explode("\n", $response->body()));
    }
}
