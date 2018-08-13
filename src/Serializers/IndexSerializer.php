<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Fields\Field;

class IndexSerializer implements JsonSerializable
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
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
        $this->resourceClass = get_class($resource);
        $this->query = $this->resource->query();
    }

    private function data()
    {
        $this->applySort();

        $result = $this->query->simplePaginate();

        $resources = [];
        foreach ($result->items() as $resource) {
            $resources[] = new $this->resourceClass($resource);
        }

        return [
            'resources' => $resources,
            'pagination' => [
                'currentPage' => $result->currentPage(),
                'hasPreviousPage' => $result->previousPageUrl() != null,
                'hasNextPage' => $result->nextPageUrl() != null,
            ]
        ];
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
            'fields' => $this->onlyFieldsOn('index'),
            'data' => $this->data(),
        ];
    }
}
