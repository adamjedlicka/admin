<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\ResourceSerializer;
use AdamJedlicka\Admin\Serializers\EditSerializer;
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

        request()->validate($resource->getRules());

        $model = $resource->fullyQualifiedModelName()::make();

        $fields = collect($resource->getFields(true))
            ->filter(function (Field $field) {
                return request($field->getName()) != null;
            })
            ->each(function (Field $field) use ($model) {
                $field->persist($model, request($field->getName()));
            });

        $model->save();

        return response()->json([
            'status' => 'success',
            'key' => $model->getKey(),
        ]);
    }

    public function detail(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);
        $resource->setModel($id);

        return new ResourceSerializer($resource);
    }

    public function edit(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);
        $resource->setModel($id);

        return new ResourceSerializer($resource);
    }

    public function update(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);

        request()->validate($resource->getRules());

        $model = $resource->fullyQualifiedModelName()::findOrFail($id);

        $fields = collect($resource->getFields(true))
            ->filter(function (Field $field) {
                return request($field->getName()) != null;
            })
            ->each(function (Field $field) use ($model) {
                $field->persist($model, request($field->getName()));
            });

        $model->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);

        $model = $resource->fullyQualifiedModelName()::findOrFail($id);

        $model->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
