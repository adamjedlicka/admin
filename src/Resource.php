<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class Resource
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $model;

    /**
     * Definition of fields
     *
     * @return array
     */
    abstract public function fields();

    /**
     * Title of the model to be displayed
     *
     * @return string
     */
    public function title()
    {
        return $this->name() . ' ' . $this->getKey();
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
     * Returns fully qualified class name of the coresponding model
     *
     * @return string
     */
    public function fullyQualifiedModelName() : string
    {
        return $this->modelNamespace() . '\\' . $this->modelName();
    }

    /**
     * Sets model or retrieves it from database based on given primary key
     *
     * @param mixed $model Model object or primary key
     */
    public function setModel($model)
    {
        if ($model instanceof Model) {
            $this->model = $model;
        } else {
            $this->model = $this->fullyQualifiedModelName()::findOrFail($model);
        }
    }

    /**
     * Returns the underlying model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel() : Model
    {
        return $this->model;
    }

    /**
     * Returns the model primary key
     *
     * @return mixed
     */
    public function key()
    {
        return $this->model->getKey();
    }

    /**
     * Creates new query over the coresponding model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query() : Builder
    {
        return $this->fullyQualifiedModelName()::query();
    }

    /**
     * Field by which resources are sorted by default
     *
     * @return string
     */
    public function sortBy() : string
    {
        return 'id';
    }

    /**
     * Order by which resources are sorted by default
     *
     * @return string
     */
    public function sortOrder() : string
    {
        return 'asc';
    }

    /**
     * Returns collection of all attributes
     *
     * @return array
     */
    public function attributes() : array
    {
        return collect($this->getFields())
            ->reduce(function ($collection, Field $field) {
                $collection[$field->getName()] = $field->resolve($this->model);
                return $collection;
            });
    }

    /**
     * Returns filtered out fields without panels
     *
     * @param bool $all Indicates whether to return fields inside panels
     * @return \Illuminate\Support\Collection
     */
    public function getFields(bool $all = false) : Collection
    {
        return collect($this->fields())
            ->filter(function ($field) use ($all) {
                return $all ? : !$field instanceof Panel;
            })
            ->map(function ($field) {
                if ($field instanceof Panel) {
                    return $field->fields();
                } else {
                    return $field;
                }
            })
            ->flatten();
    }

    /**
     * Returns only panels of current resource
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPanels() : Collection
    {
        return collect($this->fields())
            ->filter(function ($field) {
                return $field instanceof Panel;
            })
            ->values();
    }

    public function getRules() : array
    {
        return $this->getFields(true)
            ->mapWithKeys(function (Field $field) {
                return [$field->getName() => $field->getRules()];
            })
            ->toArray();
    }

    public function __get($name)
    {
        return $this->model->__get($name);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->model, $name)) {
            return call_user_func([$this->model, $name], $arguments);
        }
    }
}
