<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Resources\ListSerializer;
use AdamJedlicka\Admin\Resources\IndexSerializer;
use AdamJedlicka\Admin\Resources\DetailSerializer;

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

    public function detail(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);

        return new DetailSerializer($resource, $id);
    }
}
