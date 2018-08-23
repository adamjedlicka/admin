<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;


class IndexController extends Controller
{
    public function __invoke(string $name)
    {
        $resource = $this->getResource($name);

        return new Dial($resource->getFields('index'), $resource->indexQuery());
    }
}
