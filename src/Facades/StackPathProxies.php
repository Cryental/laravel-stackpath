<?php

namespace Cryental\StackPath\Facades;

use Cryental\StackPath\StackPathProxies;
use Illuminate\Support\Facades\Facade;

class CloudflareProxies extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return StackPathProxies::class;
    }
}
