<?php

namespace AdamJedlicka\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class ResourceService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'resourceService';
    }
}
