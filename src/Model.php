<?php

namespace AdamJedlicka\Admin;

use JsonSerializable;
use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model implements JsonSerializable
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    private $resource;

    public function __construct(Eloquent $model, Resource $resource)
    {
        $this->model = $model;
        $this->resource = $resource;
    }

    private function fields() : array
    {
        $fields = [];

        foreach ($this->resource->fields() as $field) {
            $resolved = $field->resolve($this->model);
            $fields[$field->getField()] = $resolved;
        }

        return $fields;
    }

    public function jsonSerialize()
    {
        return [
            'fields' => $this->fields(),
        ];
    }
}
