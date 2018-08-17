<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;
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
        $this->query = $this->resource->query();
    }

    private function data()
    {
        $this->applySort();

        $result = $this->query->simplePaginate();

        foreach ($result->items() as $model) {
            $resources[] = (new ResourceSerializer($this->resource, $model))
                ->view('index');
        }

        return [
            'resources' => $resources ?? [],
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
