<?php

namespace AdamJedlicka\Admin\Resources;

use JsonSerializable;

class DetailSerializer implements JsonSerializable
{
    /**
     * @var \AdamJedlicka\Admin\Resources\Resource
     */
    private $resource;

    /**
     * @var mixed
     */
    private $id;

    public function __construct(Resource $resource, $id)
    {
        $this->resource = $resource;
        $this->id = $id;
    }

    private function model()
    {
        return $this->resource->model()::find($this->id);
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->resource->name(),
            'displayName' => $this->resource->displayName(),
            'fields' => $this->resource->fields(),
            'model' => $this->model(),
        ];
    }
}
