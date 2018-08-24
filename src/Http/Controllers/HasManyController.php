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
        $relatedField = $resource->getField($relationship)->getRelatedField($resource);

        $fields = $relatedResource->getFields('index')
            ->filter(function ($field) use ($relatedField) {
                return $field->getName() != $relatedField->getName();
            })
            ->values();

        return (new Dial($fields, $query))
            ->detailUrl("/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}")
            ->editUrl("/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}/edit")
            ->deleteUrl("/api/resources/{$relatedResource->name()}/\${{$relatedModel->getKeyName()}}");
    }
}
