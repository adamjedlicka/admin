<?php

namespace AdamJedlicka\Admin\Http\Controllers;

class BelongsToController extends Controller
{
    public function index(string $resource)
    {
        $resource = $this->getResource($resource);
        $models = $resource->model()::all();

        return $models
            ->map(function ($model) use ($resource) {
                $resource->setModel($model);

                return [
                    'key' => $model->getKey(),
                    'title' => $resource->title(),
                ];
            });
    }
}
