<?php

namespace AdamJedlicka\Admin\Http\Controllers;

class BelongsToController extends Controller
{
    public function index(string $resource, string $relationship)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::make();
        $relationship = $model->$relationship();

        $relatedResource = $this->getResource($relationship->getRelated());
        $relatedModels = $relatedResource->model()::all();

        return $relatedModels
            ->map(function ($relatedModel) use ($relatedResource) {
                $relatedResource->setModel($relatedModel);

                return [
                    'key' => $relatedModel->getKey(),
                    'title' => $relatedResource->title(),
                ];
            });
    }
}
