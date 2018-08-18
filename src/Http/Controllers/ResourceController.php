<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\ResourceService;
use AdamJedlicka\Admin\Serializers\ListSerializer;
use AdamJedlicka\Admin\Serializers\IndexSerializer;
use AdamJedlicka\Admin\Serializers\CreateSerializer;
use AdamJedlicka\Admin\Serializers\ResourceSerializer;

class ResourceController extends Controller
{
    /**
     * @var \AdamJedlicka\Admin\ResourceService
     */
    public $service;

    public function __construct(ResourceService $service)
    {
        $this->service = $service;
    }

    public function list()
    {
        return new ListSerializer();
    }

    public function index(string $name)
    {
        $resource = $this->service->getResourceFromName($name);

        return new IndexSerializer($resource);
    }

    public function create(string $name)
    {
        $resource = $this->service->getResourceFromName($name);

        return new CreateSerializer($resource);
    }

    public function store(string $name)
    {
        $resource = $this->service->getResourceFromName($name);
        $model = $resource->model()::make();

        request()->validate($resource->getCreationRules());

        $model = $this->service->persistModel($resource, $model);

        return response()->json([
            'status' => 'success',
            'key' => $model->getKey(),
        ]);
    }

    public function detail(string $name, $key)
    {
        $resource = $this->service->getResourceFromName($name);
        $model = $resource->model()::findOrFail($key);

        return (new ResourceSerializer($resource, $model))
            ->view('detail');
    }

    public function edit(string $name, $key)
    {
        $resource = $this->service->getResourceFromName($name);
        $model = $resource->model()::findOrFail($key);

        return (new ResourceSerializer($resource, $model))
            ->view('edit');
    }

    public function update(string $name, $key)
    {
        $resource = $this->service->getResourceFromName($name);

        request()->validate($resource->getUpdateRules());

        $model = $resource->model()::findOrFail($key);

        $this->service->persistModel($resource, $model);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete(string $name, $key)
    {
        $resource = $this->service->getResourceFromName($name);

        $model = $resource->model()::findOrFail($key);

        $model->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
