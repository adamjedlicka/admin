<?php

namespace AdamJedlicka\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class Resources extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'admin.resources';
    }
}
