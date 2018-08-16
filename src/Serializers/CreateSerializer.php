<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;
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

    public function toArray()
    {
        return [
            'name' => $this->resource->name(),
            'fields' => $this->resource->getFields('edit'),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
