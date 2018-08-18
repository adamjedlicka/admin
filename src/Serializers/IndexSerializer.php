<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;

class IndexSerializer implements Arrayable, JsonSerializable
{
    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $resource;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $indexQuery;

    /**
     * @var array
     */
    protected $resources = [];

    /**
     * @var array
     */
    protected $pagination = [];

    public function __construct(Resource $resource, $indexQuery = null)
    {
        $this->resource = $resource;
        $this->indexQuery = $indexQuery ?? $this->resource->indexQuery();

        $this->compute();
    }

    private function compute()
    {
        $this->applySort();

        $result = $this->indexQuery->simplePaginate();

        $this->pagination = [
            'currentPage' => $result->currentPage(),
            'hasPreviousPage' => $result->previousPageUrl() != null,
            'hasNextPage' => $result->nextPageUrl() != null,
        ];

        foreach ($result->items() as $model) {
            $this->resources[] = (new ResourceSerializer($this->resource, $model))
                ->view('index');
        }
    }

    private function applySort()
    {
        $this->indexQuery->orderBy(
            request('sortBy', $this->resource->sortBy()),
            request('orderBy', $this->resource->sortOrder())
        );
    }

    public function toArray()
    {
        return [
            'name' => $this->resource->name(),
            'pluralName' => $this->resource->pluralName(),
            'fields' => $this->resource->getFields('index'),
            'resources' => $this->resources,
            'pagination' => $this->pagination,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
