<?php

namespace Cryental\StackPath\Commands;

use Cryental\StackPath\LaravelStackPath;
use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Config\Repository;

class Reload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stackpath:reload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reload trust proxies IPs and store in cache.';

    /**
     * Execute the console command.
     *
     * @param  Factory  $cache
     * @param  Repository  $config
     * @return void
     */
    public function handle(Factory $cache, Repository $config)
    {
        $proxies = LaravelStackPath::getProxies();

        $cache->store()->forever($config->get('laravelstackpath.cache'), $proxies);

        $this->info('StackPath\'s IP blocks have been reloaded.');
    }
}
