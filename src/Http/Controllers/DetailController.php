<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Detail;

class DetailController extends Controller
{
    public function __invoke(string $resource, $key)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($key);
        $resource->setModel($model);

        return (new Detail($resource->getFields('detail'), $model))
            ->title($resource->title())
            ->editUrl("/resources/{$resource->name()}/{$model->getKey()}/edit")
            ->deleteUrl("/api/resources/{$resource->name()}/{$model->getKey()}");
    }
}
