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

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    protected function fillFields(Collection $fields) : Collection
    {
        return $fields->map(function (Field $field) {
            return array_merge($field->toArray(), [
                'value' => $field->retrieve($this->resource->getModel()),
            ]);
        });
    }

    protected function filledFields() : Collection
    {
        return $this->fillFields($this->resource->getFields());
    }

    protected function filledPanels()
    {
        return $this->resource->getPanels()
            ->map(function (Panel $panel) {
                $arr = $panel->toArray();

                $arr['fields'] = $this->fillFields(collect($arr['fields']));

                return $arr;
            });
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

    public function toArray()
    {
        return [
            'name' => $this->resource->name(),
            'title' => $this->resource->title(),
            'model' => $this->resource->getModel(),
            'key' => $this->resource->getModel()->getKey(),
            'fields' => $this->filledFields(),
            'panels' => $this->filledPanels(),
        ];
    }

    public function jsonSerialize()
    {
        return count($this->only) == 0
            ? $this->toArray()
            : collect($this->toArray())->only($this->only);
    }
}
