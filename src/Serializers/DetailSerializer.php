<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Resources\Resource;

class DetailSerializer implements JsonSerializable
{
    use SerializesResources;

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
        $model = $this->resource->query()->find($this->id);

        $this->fillComputedFields($model);

        return $model;
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
