<?php

namespace AdamJedlicka\Admin;

use JsonSerializable;
use AdamJedlicka\Admin\Resource;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;

class ResourceSerializer implements JsonSerializable
{
    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    protected function fillFields(Collection $fields) : Collection
    {
        return $fields->map(function (Field $field) {
            return array_merge($field->toArray(), [
                'value' => $field->resolve($this->resource->getModel()),
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

    public function jsonSerialize() : array
    {
        return [
            'name' => $this->resource->name(),
            'title' => $this->resource->title(),
            'model' => $this->resource->getModel(),
            'fields' => $this->filledFields(),
            'panels' => $this->filledPanels(),
        ];
    }
}
