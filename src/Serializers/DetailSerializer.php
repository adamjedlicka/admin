<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Resource;

class DetailSerializer implements JsonSerializable
{
    use SerializesResources;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    private $resource;

    /**
     * @var string
     */
    private $resourceClass;

    /**
     * @var mixed
     */
    private $id;

    public function __construct(Resource $resource, $id)
    {
        $this->resource = $resource;
        $this->resourceClass = get_class($resource);
        $this->id = $id;
    }

    private function resource()
    {
        $model = $this->resource->query()
            ->select($this->onlyFieldNamesOn('detail'))
            ->find($this->id);

        return new $this->resourceClass($model);
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->resource->name(),
            'displayName' => $this->resource->name(),
            'fields' => $this->onlyFieldsOn('detail'),
            'resource' => $this->resource(),
        ];
    }
}
