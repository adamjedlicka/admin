<?php

namespace AdamJedlicka\Admin\Resources;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class Resource
{
    /**
     * Definition of fields
     *
     * @return array
     */
    abstract public function fields() : array;

    /**
     * Returns collection of all regular, non-computed fields
     *
     * @return \Illuminate\Support\Collection
     */
    public function regularFields() : Collection
    {
        return collect($this->fields())
            ->filter(function (Field $field) {
                return $field->getCallable() == null;
            });
    }

    /**
     * Returns collection of all computed fields
     *
     * @return \Illuminate\Support\Collection
     */
    public function computedFields() : Collection
    {
        return collect($this->fields())
            ->filter(function (Field $field) {
                return $field->getCallable() != null;
            });
    }

    /**
     * Name of the resource
     *
     * @return string
     */
    public function name() : string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Display name of the resource. By default plural version of the model name
     *
     * @return string
     */
    public function displayName() : string
    {
        return Str::plural($this->name());
    }

    /**
     * Returns the name of the model.
     * By default generated from the name of the current resource.
     *
     * @return string
     */
    public function modelName() : string
    {
        return $this->name();
    }

    /**
     * Returns the namespace of the model.
     * By default generated from the name of the current resource.
     *
     * @return string
     */
    public function modelNamespace() : string
    {
        return config('admin.models.namespace');
    }

    /**
     * Returns class of the coresponding model
     *
     * @return string
     */
    public function model() : string
    {
        return $this->modelNamespace() . '\\' . $this->modelName();
    }

    /**
     * Creates new query over the coresponding model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query() : Builder
    {
        return $this->model()::query();
    }
}
