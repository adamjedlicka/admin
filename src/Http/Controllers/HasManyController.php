<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;
use AdamJedlicka\Admin\Facades\ResourceService;
use AdamJedlicka\Admin\Serializers\IndexSerializer;

class HasManyController extends Controller
{
    public function index(string $name, $key, string $relationship)
    {
        $resource = ResourceService::getResourceFromName($name);
        $model = $resource->model()::findOrFail($key);
        $query = $model->{$relationship}();

        $relatedModel = $query->getRelated();
        $relatedResource = ResourceService::getResourceFromModel($relatedModel);

        return (new Dial($relatedResource->getFields('index'), $query))
            ->detailUrl("/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}")
            ->editUrl("/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}/edit")
            ->deleteUrl("/api/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}");
    }
}
