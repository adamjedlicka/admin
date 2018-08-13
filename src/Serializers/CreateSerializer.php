<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;

class CreateSerializer implements JsonSerializable
{
    use SerializesResources;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    private $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->resource->name(),
            'fields' => $this->onlyFieldsOn('edit'),
        ];
    }
}
