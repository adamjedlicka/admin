<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Routing\Controller as BaseController;
use function AdamJedlicka\Admin\Support\get_resource_from_name;

class Controller extends BaseController
{
    /**
     * Locates and returns resource based on name
     *
     * @return \AdamJedlicka\Admin\Resource
     */
    public function getResourceFromName(string $name)
    {
        return get_resource_from_name($name);
    }
}
