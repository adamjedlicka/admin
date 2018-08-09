<?php

namespace AdamJedlicka\Admin\Resources;

use JsonSerializable;
use AdamJedlicka\Admin\Fields\Field;

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
        $query = $this->resource->query();

        $fields = $this->resource->regularFields()
            ->map(function (Field $field) {
                return $field->getField();
            });

        $query->select($fields->toArray());

        if ($sortBy = request('sortBy')) {
            $query->orderBy($sortBy, request('orderBy', 'asc'));
        }

        $items = $query->paginate();

        foreach ($items as $item) {
            foreach ($this->resource->computedFields() as $field) {
                $result = call_user_func($field->getCallable(), $item);
                $item->setAttribute($field->getField(), $result);
            }
        }

        return $items;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->resource->name(),
            'displayName' => $this->resource->displayName(),
            'fields' => $this->resource->fields(),
            'data' => $this->data(),
        ];
    }
}
