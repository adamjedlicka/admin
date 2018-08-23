<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Facades\ResourceService;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Returns new instance of resource based on parameter
     *
     * @param mixed $resource
     * @return \AdamJedlicka\Admin\Resource
     */
    protected function getResource($resource) : Resource
    {
        if (is_string($resource)) {
            return ResourceService::getResourceFromName($resource);
        }
    }
}
