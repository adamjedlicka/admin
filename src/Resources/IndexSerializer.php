<?php

namespace AdamJedlicka\Admin\Resources;

use JsonSerializable;

class IndexSerializer implements JsonSerializable
{
    /**
     * @var \AdamJedlicka\Admin\Resources\Resource
     */
    private $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function jsonSerialize()
    {
        return [
            'name' => (new \ReflectionClass($this->resource))->getShortName(),
            'displayName' => $this->resource->displayName(),
            'fields' => $this->resource->fields(),
        ];
    }
}
