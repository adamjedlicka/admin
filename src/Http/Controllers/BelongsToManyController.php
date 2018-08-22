<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\Facades\ResourceService;
use AdamJedlicka\Admin\Serializers\IndexSerializer;

class BelongsToManyController extends Controller
{
    public function index(string $name, $key, string $relationship)
    {
        $resource = ResourceService::getResourceFromname($name);
        $model = $resource->model()::findOrFail($key);

        $query = $model->{$relationship}();
        $relatedResource = ResourceService::getResourceFromModel(
            $query->getRelated()
        );

        return (new IndexSerializer($relatedResource, $query))
            ->extraFields($resource->getField($relationship)->getFields());
    }

    public function create(string $name, $key, string $relationship)
    {
        $resource = ResourceService::getResourceFromname($name);
        $model = $resource->model()::findOrFail($key);
        $query = $model->{$relationship}();
        $relatedResource = ResourceService::getResourceFromModel(
            $query->getRelated()
        );

        return [
            'relatedResource' => $relatedResource->name(),
            'fields' => collect($resource->getField($relationship)->getFields())
                ->map(function (Field $field) {
                    return $field->toArray();
                }),
        ];
    }

    public function attach(string $name, $key, string $relationship, $relatedKey)
    {
        $resource = ResourceService::getResourceFromname($name);
        $model = $resource->model()::findOrFail($key);

        $model->{$relationship}()->attach($relatedKey, request()->all());

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function detach(string $name, $key, string $relationship, $relatedKey)
    {
        $resource = ResourceService::getResourceFromname($name);
        $model = $resource->model()::findOrFail($key);

        $model->{$relationship}()->detach($relatedKey);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
