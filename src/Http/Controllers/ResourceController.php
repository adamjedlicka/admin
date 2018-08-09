<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Serializers\ListSerializer;
use AdamJedlicka\Admin\Serializers\IndexSerializer;
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
}
