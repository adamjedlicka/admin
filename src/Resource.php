<?php

namespace AdamJedlicka\Admin;

use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class Resource implements JsonSerializable
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $model;

    public function __construct(? Model $model = null)
    {
        $this->model = $model;
    }

    /**
     * Definition of fields
     *
     * @return array
     */
    abstract public function fields() : array;

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
     * Returns array of fields and their rules for validation
     *
     * @return array
     */
    public function rules() : array
    {
        $rules = [];

        foreach ($this->fields() as $field) {
            $fieldRules = $field->getRules();
            if (count($fieldRules) > 0) {
                $rules[$field->getName()] = $fieldRules;
            }
        }

        return $rules;
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
     * Title of the model to be displayed
     */
    public function title()
    {
        return $this->getKey();
    }

    public function jsonSerialize() : array
    {
        return [
            'attributes' => $this->attributes(),
            'key' => $this->getKey(),
            'title' => $this->title(),
        ];
    }

    /**
     * Returns filtered out fields without panels
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFields() : Collection
    {
        return collect($this->fields())
            ->filter(function ($field) {
                return !$field instanceof Panel;
            })
            ->values();
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
