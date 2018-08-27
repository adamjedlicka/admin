<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Detail;
use AdamJedlicka\Admin\Http\Requests\DetailRequest;

class DetailController extends Controller
{
    public function __invoke(DetailRequest $request)
    {
        $resource = $request->resource();
        $model = $resource->getModel();

        return (new Detail($resource->getFields('detail'), $model))
            ->title($resource->title())
            ->editUrl("/resources/{$resource->name()}/{$model->getKey()}/edit")
            ->deleteUrl("/api/resources/{$resource->name()}/{$model->getKey()}");
    }
}
