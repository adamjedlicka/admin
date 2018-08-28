<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Facades\Resources;


class BelongsToController extends Controller
{
    public function index(string $resource, string $relationship)
    {
        $resource = Resources::forName($resource);
        $model = $resource->newModel();
        $relationship = $model->$relationship();

        $relatedResource = Resources::forModel($relationship->getRelated());
        $relatedModels = $relatedResource::$model::all();

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
