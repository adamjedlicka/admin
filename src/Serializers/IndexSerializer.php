<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\Resources\Resource;

class IndexSerializer implements JsonSerializable
{
    use SerializesResources;

    /**
     * @var \AdamJedlicka\Admin\Resources\Resource
     */
    private $resource;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
        $this->query = $this->resource->query();
    }

    private function data()
    {
        $this->selectOnlyRegularFields();

        $this->applySort();

        $items = $this->query->paginate();

        foreach ($items as $item) {
            $this->fillComputedFields($item);
        }

        return $items;
    }

    private function selectOnlyRegularFields()
    {
        $fields = $this->resource->regularFields()
            ->map(function (Field $field) {
                return $field->getField();
            });

        // We need to add field by which the collection is sorted
        // in case it's not in the regular fields list.
        $fields->push($this->resource->sortBy());

        $this->query->select($fields->toArray());
    }

    private function applySort()
    {
        $this->query->orderBy(
            request('sortBy', $this->resource->sortBy()),
            request('orderBy', $this->resource->sortOrder())
        );
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
