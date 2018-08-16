<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\ResourceService;
use AdamJedlicka\Admin\ResourceSerializer;
use AdamJedlicka\Admin\Serializers\EditSerializer;
use AdamJedlicka\Admin\Serializers\ListSerializer;
use AdamJedlicka\Admin\Serializers\IndexSerializer;
use AdamJedlicka\Admin\Serializers\CreateSerializer;
use AdamJedlicka\Admin\Serializers\DetailSerializer;

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

        request()->validate($resource->getRules());

        $model = DB::transaction(function () use ($resource) {
            $model = $resource->fullyQualifiedModelName()::make();

            $fields = collect($resource->getFields(true))
                ->filter(function (Field $field) {
                    return $field->isVisibleOn('edit');
                })
                ->each(function (Field $field) use ($model) {
                    $field->persist($model, request($field->getName()));
                });

            $model->saveOrFail();

            return $model;
        });

        return response()->json([
            'status' => 'success',
            'key' => $model->getKey(),
        ]);
    }

    public function detail(string $name, $key)
    {
        $resource = $this->service->getResourceFromName($name);
        $resource->setModel($key);

        return (new ResourceSerializer($resource))
            ->view('detail');
    }

    public function edit(string $name, $key)
    {
        $resource = $this->service->getResourceFromName($name);
        $resource->setModel($key);

        return (new ResourceSerializer($resource))
            ->view('edit');
    }

    public function update(string $name, $key)
    {
        $resource = $this->service->getResourceFromName($name);

        request()->validate($resource->getRules());

        $model = $resource->fullyQualifiedModelName()::findOrFail($key);

        $this->service->persistModel($resource, $model);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete(string $name, $key)
    {
        $resource = $this->service->getResourceFromName($name);

        $model = $resource->fullyQualifiedModelName()::findOrFail($key);

        $model->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
