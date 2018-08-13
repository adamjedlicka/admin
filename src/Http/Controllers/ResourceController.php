<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Serializers\ListSerializer;
use AdamJedlicka\Admin\Serializers\IndexSerializer;
use AdamJedlicka\Admin\Serializers\CreateSerializer;
use AdamJedlicka\Admin\Serializers\DetailSerializer;

class ResourceController extends Controller
{
    public function list()
    {
        return new ListSerializer();
    }

    public function index(string $name)
    {
        $resource = $this->getResourceFromName($name);

        return new IndexSerializer($resource);
    }

    public function create(string $name)
    {
        $resource = $this->getResourceFromName($name);

        return new CreateSerializer($resource);
    }

    public function store(string $name)
    {
        $resource = $this->getResourceFromName($name);

        request()->validate($resource->rules());

        $model = $resource->model()::create(request()->all());

        return response()->json([
            'status' => 'success',
            'id' => $model->id,
        ]);
    }

    public function detail(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);

        return new DetailSerializer($resource, $id);
    }

    public function update(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);

        request()->validate($resource->rules());

        $model = $resource->model()::findOrFail($id);
        $model->update(request()->all());

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);

        $model = $resource->model()::findOrFail($id);

        $model->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
