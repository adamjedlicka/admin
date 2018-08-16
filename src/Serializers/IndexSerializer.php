<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\ResourceSerializer;
use Illuminate\Contracts\Support\Arrayable;

class IndexSerializer implements Arrayable, JsonSerializable
{
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
        foreach ($result->items() as $item) {
            $resource = new $this->resourceClass;
            $resource->setModel($item);

            $resources[] = (new ResourceSerializer($resource))
                ->view('index');
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

    public function toArray()
    {
        $data = $this->data();

        return [
            'name' => $this->resource->name(),
            'displayName' => $this->resource->displayName(),
            'fields' => $this->resource->getFields('index'),
            'resources' => $data['resources'],
            'pagination' => $data['pagination'],
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
