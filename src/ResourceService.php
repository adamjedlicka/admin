<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Support\Facades\DB;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ResourceService
{
    /**
     * Creates and returns new resource based on the name parameter
     *
     * @param string $name
     * @return \AdamJedlicka\Admin\Resource
     */
    public function getResourceFromName(string $name) : Resource
    {
        $fileName = Str::studly($name) . '.php';
        $path = app_path(config('admin.directory') . '/Resources');

        $class = get_class_from_file($path . '/' . $fileName);

        return new $class;
    }

    /**
     * Creates and returns new resource based on the model parameter
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \AdamJedlicka\Admin\Resource
     */
    public function getResourceFromModel(Model $model) : Resource
    {
        $name = (new \ReflectionClass($model))->getShortName();

        $resource = $this->getResourceFromName($name);
        if ($model->exists) $resource->setModel($model);

        return $resource;
    }

    /**
     * Persists the HTTP request into the model
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function persistModel(Resource $resource, Model $model) : Model
    {
        return DB::transaction(function () use ($resource, $model) {
            collect($resource->getFields())
                ->filter(function (Field $field) {
                    return $field->isVisibleOn('edit');
                })
                ->each(function (Field $field) use ($model) {
                    $field->persist($model, Request::get($field->getName()));
                });

            $model->saveOrFail();

            return $model;
        });
    }
}
