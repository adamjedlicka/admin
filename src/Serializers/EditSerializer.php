<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;

class EditSerializer implements JsonSerializable
{
    use SerializesResources;

    /**
     * @var \AdamJedlicka\Admin\Resource
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
        $model = $this->resource->query()
            ->select($this->onlyFieldNamesOn('edit'))
            ->find($this->id);

        return new Model($model, $this->resource);
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->resource->name(),
            'fields' => $this->onlyFieldsOn('edit'),
            'model' => $this->model(),
        ];
    }
}
