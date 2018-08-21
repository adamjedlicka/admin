<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Contracts\Support\Arrayable;

class CreateSerializer implements Arrayable, JsonSerializable
{
    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    private $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function fields() : Collection
    {
        return $this->resource->getFields('edit')
            ->each(function (Field $field) {
                $field->export([
                    'meta' => $field->meta($this->resource),
                ]);
            })
            ->values();
    }

    public function toArray()
    {
        return [
            'name' => $this->resource->name(),
            'fields' => $this->fields(),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
