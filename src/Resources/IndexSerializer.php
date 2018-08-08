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

    private function data()
    {
        return $this->resource->model()::paginate();
    }

    public function jsonSerialize()
    {
        return [
            'name' => (new \ReflectionClass($this->resource))->getShortName(),
            'displayName' => $this->resource->displayName(),
            'fields' => $this->resource->fields(),
            'data' => $this->data(),
        ];
    }
}
