<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Detail;


class DetailController extends Controller
{
    public function __invoke(string $resource, $key)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($key);

        return new Detail($resource->getField('detail'), $model);
    }
}
