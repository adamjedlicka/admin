<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Resources\ListSerializer;
use AdamJedlicka\Admin\Resources\IndexSerializer;

class ResourceController extends Controller
{
    public function list()
    {
        return new ListSerializer();
    }

    public function index(string $name)
    {
        $resource = $this->getResourceFromName($name);

        return new IndexSerializer($resource);
    }
}
