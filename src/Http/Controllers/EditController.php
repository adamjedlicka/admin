<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Edit;


class EditController extends Controller
{
    public function __invoke(string $resource, $key)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($key);
        $resource->setModel($model);

        return (new Edit($resource->getFields('edit'), $model))
            ->title($resource->title())
            ->updateUrl("/api/resources/{$resource->name()}/{$model->getKey()}");
    }
}
