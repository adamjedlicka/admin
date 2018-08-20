<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Facades\ResourceService;
use AdamJedlicka\Admin\Serializers\ListSerializer;
use AdamJedlicka\Admin\Serializers\IndexSerializer;
use AdamJedlicka\Admin\Serializers\CreateSerializer;
use AdamJedlicka\Admin\Serializers\ResourceSerializer;

class ResourceController extends Controller
{
    public function list()
    {
        return new ListSerializer();
    }

    public function index(string $name)
    {
        $resource = ResourceService::getResourceFromName($name);

        return new IndexSerializer($resource);
    }

    public function create(string $name)
    {
        $resource = ResourceService::getResourceFromName($name);

        return new CreateSerializer($resource);
    }

    public function store(string $name)
    {
        $resource = ResourceService::getResourceFromName($name);
        $model = $resource->model()::make();

        request()->validate($resource->getCreationRules());

        $model = ResourceService::persistModel($resource, $model);

        return response()->json([
            'status' => 'success',
            'key' => $model->getKey(),
        ]);
    }

    public function detail(string $name, $key)
    {
        $resource = ResourceService::getResourceFromName($name);
        $model = $resource->model()::findOrFail($key);

        return (new ResourceSerializer($resource, $model))
            ->view('detail');
    }

    public function edit(string $name, $key)
    {
        $resource = ResourceService::getResourceFromName($name);
        $model = $resource->model()::findOrFail($key);

        return (new ResourceSerializer($resource, $model))
            ->view('edit');
    }

    public function update(string $name, $key)
    {
        $resource = ResourceService::getResourceFromName($name);

        request()->validate($resource->getUpdateRules());

        $model = $resource->model()::findOrFail($key);

        ResourceService::persistModel($resource, $model);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete(string $name, $key)
    {
        $resource = ResourceService::getResourceFromName($name);

        $model = $resource->model()::findOrFail($key);

        $model->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
