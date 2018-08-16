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
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getModel() : ? Model
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
     * Returns filtered out fields without panels
     *
     * @param string|null $view
     * @return \Illuminate\Support\Collection
     */
    public function getFields(? string $view = null) : Collection
    {
        return collect($this->fields())
            ->map(function ($field) {
                if ($field instanceof Panel) {
                    return $field->fields();
                } else {
                    return $field;
                }
            })
            ->flatten()
            ->filter(function (Field $field) use ($view) {
                return $view == null ? : $field->isVisibleOn($view);
            })
            ->each(function (Field $field) {
                $field->setResource($this);
            })
            ->values();
    }

    /**
     * Returns computed array of all rules
     *
     * @return array
     */
    public function getRules() : array
    {
        return $this->getFields()
            ->mapWithKeys(function (Field $field) {
                return [$field->getName() => $field->getRules()];
            })
            ->toArray();
    }

    /**
     * Returns resource title or empty string whether resopurce has model
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->model && $this->model->exists
            ? $this->title()
            : '';
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
