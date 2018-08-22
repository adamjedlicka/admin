<?php

namespace AdamJedlicka\Admin\Http\Controllers;

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

        return (new IndexSerializer($relatedResource, $query));
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
        ];
    }

    public function store(string $name, $key, string $relationship)
    {
        $resource = ResourceService::getResourceFromname($name);
        $model = $resource->model()::findOrFail($key);

        $response = $model->{$relationship}()->attach(request('key'));

        return response()->json([
            'status' => 'success',
            'response' => $response,
        ]);
    }
}
