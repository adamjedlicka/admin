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
     * Removes certain fields from exporting
     *
     * @param mixed $exceptFields
     */
    public function exceptFields(...$exceptFields) : self
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

    protected function fields()
    {
        return $this->resource->getFields($this->view)
            ->filter(function (Field $field) {
                return !in_array($field->getName(), $this->exceptFields);
            })
            ->values();
    }

    public function toArray()
    {
        return [
            'name' => $this->resource->name(),
            'title' => $this->resource->title(),
            'key' => $this->resource->getModel()->getKey(),
            'fields' => $this->fields(),
        ];
    }

    public function jsonSerialize()
    {
        return count($this->only) == 0
            ? $this->toArray()
            : collect($this->toArray())->only($this->only);
    }
}
