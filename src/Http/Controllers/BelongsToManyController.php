<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;

class BelongsToManyController extends Controller
{
    public function index(string $resource, $key, string $relationship)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($key);

        $fields = $resource->getField($relationship)->getFields($resource);
        $query = $model->$relationship();

        return (new Dial($fields, $query));
    }
}
