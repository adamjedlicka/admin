<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Fields\Field;
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

        request()->validate($resource->rules());

        $model = $resource->fullyQualifiedModelName()::make();

        $fields = collect($resource->fields())
            ->filter(function (Field $field) {
                return request($field->getName()) != null;
            })
            ->each(function (Field $field) use ($model) {
                $field->persist($model, request($field->getName()));
            });

        $model->save();

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

    public function edit(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);

        return new EditSerializer($resource, $id);
    }

    public function update(string $name, $id)
    {
        $resource = $this->getResourceFromName($name);

        request()->validate($resource->rules());

        $model = $resource->fullyQualifiedModelName()::findOrFail($id);

        $fields = collect($resource->fields())
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
