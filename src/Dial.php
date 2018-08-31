<?php

namespace AdamJedlicka\Luna;

use AdamJedlicka\Luna\Traits\HasLinks;
use AdamJedlicka\Luna\Facades\Resources;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;

class Dial implements Arrayable
{
    use HasLinks;

    /**
     * @var \AdamJedlicka\Luna\Resource
     */
    protected $resource;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * @var \AdamJedlicka\Luna\FieldCollection
     */
    protected $hiddenFields;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
        $this->query = $this->resource->indexQuery();

        $this->hiddenFields = new FieldCollection([]);
    }

    /**
     * Sets query which is used to display dial data
     *
     * @param mixed $query
     * @return self
     */
    public function query($query) : self
    {
        $this->query = $query;

        return $this;
    }

    public function hideFields(...$fields) : self
    {
        $this->hiddenFields = new FieldCollection($fields);

        return $this;
    }

    protected function data()
    {
        if ($sortBy = request('sortBy')) {
            $this->query->orderBy($sortBy, request('orderBy', 'asc'));
        }

        return $this->paginated();
    }

    protected function paginated()
    {
        $paginated = $this->query->simplePaginate();

        $data = collect($paginated->items())
            ->map(function ($item) {
                return Resources::forModel($item)
                    ->onlyFields($this->fields());
            });

        $pagination = [
            'currentPage' => $paginated->currentPage(),
            'hasNextPage' => $paginated->nextPageUrl() != null,
            'hasPreviousPage' => $paginated->previousPageUrl() != null,
        ];

        return [$data, $pagination];
    }

    protected function fields() : FieldCollection
    {
        return $this->resource->getFields()
            ->onlyFor('index')
            ->filter(function ($field) {
                return array_search($field->getName(), $this->hiddenFields->names()) === false;
            })
            ->values();
    }

    public function toArray()
    {
        [$data, $pagination] = $this->data();

        return [
            'fields' => $this->fields(),
            'data' => $data,
            'pagination' => $pagination,
            'links' => $this->links,
        ];
    }
}
