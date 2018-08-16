<?php

namespace AdamJedlicka\Admin;

use JsonSerializable;
use AdamJedlicka\Admin\Resource;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
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

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    protected function fields() : Collection
    {
        return collect($this->resource->fields())
            ->filter(function (Field $field) {
                return $this->view == null ? : $field->isVisibleOn($this->view);
            })
            ->each(function (Field $field) {
                $field->setResource($this->resource);
            })
            ->values();
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
     * Filters out fields for specific view
     *
     * @return self
     */
    public function view(string $view) : self
    {
        $this->view = $view;

        return $this;
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
