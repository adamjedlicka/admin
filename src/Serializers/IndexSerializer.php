<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use AdamJedlicka\Admin\Model;
use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Fields\Field;
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

    /**
     * @var array
     */
    protected $extraFields = [];

    /**
     * @var array
     */
    protected $exceptFields = [];

    public function __construct(Resource $resource, $indexQuery = null)
    {
        $this->resource = $resource;
        $this->indexQuery = $indexQuery ?? $this->resource->indexQuery();
    }

    public function extraFields(array $extraFields) : self
    {
        $this->extraFields = $extraFields;

        return $this;
    }

    public function exceptFields(array $exceptFields) : self
    {
        $this->exceptFields = $exceptFields;

        return $this;
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
                ->exceptFields($this->exceptFields)
                ->extraFields($this->extraFields)
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

    private function fields()
    {
        return $this->resource->getFields('index')
            ->filter(function (Field $field) {
                return !in_array($field->getName(), $this->exceptFields);
            })
            ->merge($this->extraFields)
            ->values();
    }

    public function toArray()
    {
        $this->compute();

        return [
            'name' => $this->resource->name(),
            'pluralName' => $this->resource->pluralName(),
            'fields' => $this->fields(),
            'resources' => $this->resources,
            'pagination' => $this->pagination,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
