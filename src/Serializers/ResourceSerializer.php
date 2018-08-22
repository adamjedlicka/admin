<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Resource;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

class ResourceSerializer implements Arrayable, JsonSerializable
{
    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $resource;

    /**
     * @var array
     */
    protected $only = [];

    /**
     * @var string|null
     */
    protected $view = null;

    /**
     * @var array
     */
    protected $extraFields = [];

    /**
     * @var array
     */
    protected $exceptFields = [];

    public function __construct(Resource $resource, Model $model)
    {
        $this->resource = clone $resource;
        $this->resource->setModel($model);
    }

    /**
     * Limits what is send to frontend
     *
     * @return self
     */
    public function only(...$only) : self
    {
        $this->only = $only;

        return $this;
    }

    /**
     * Export extra fields. For example for pivot table
     *
     * @param array $extraFields
     * @return self
     */
    public function extraFields(array $extraFields) : self
    {
        $this->extraFields = $extraFields;

        return $this;
    }

    /**
     * Removes certain fields from exporting
     *
     * @param array $exceptFields
     * @return self
     */
    public function exceptFields(array $exceptFields) : self
    {
        $this->exceptFields = $exceptFields;

        return $this;
    }

    /**
     * Filters out fields for specific view
     *
     * @return self
     */
    public function view(string $view) : self
    {
        $this->view = $view;
        $this->resource->view = $view;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function fields() : Collection
    {
        return $this->resource->getFields($this->view)
            ->filter(function (Field $field) {
                return !in_array($field->getName(), $this->exceptFields);
            })
            ->merge($this->extraFields)
            ->each(function (Field $field) {
                $field->export([
                    'meta' => $field->meta($this->resource),
                    'value' => $field->value($this->resource, $this->resource->getModel()),
                ]);
            })
            ->values();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function model() : Collection
    {
        return $this->fields()
            ->mapWithKeys(function (Field $field) {
                return [$field->getName() => $field->retrieve($this->resource->getModel())];
            });
    }

    public function toArray()
    {
        return [
            'name' => $this->resource->name(),
            'title' => $this->resource->title(),
            'key' => $this->resource->getModel()->getKey(),
            'fields' => $this->fields(),
            'model' => $this->model(),
        ];
    }

    public function jsonSerialize()
    {
        return count($this->only) == 0
            ? $this->toArray()
            : collect($this->toArray())->only($this->only);
    }
}
