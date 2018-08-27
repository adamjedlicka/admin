<?php

namespace AdamJedlicka\Admin;

use AdamJedlicka\Admin\Traits\HasLinks;
use AdamJedlicka\Admin\Facades\Resources;
use Illuminate\Contracts\Support\Arrayable;

class Dial implements Arrayable
{
    use HasLinks;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $resource;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
        $this->query = $this->resource->indexQuery();
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
                    ->onlyFieldsFor('index');
            });

        $pagination = [
            'currentPage' => $paginated->currentPage(),
            'hasNextPage' => $paginated->nextPageUrl() != null,
            'hasPreviousPage' => $paginated->previousPageUrl() != null,
        ];

        return [$data, $pagination];
    }

    protected function fields()
    {
        return $this->resource->getFields()
            ->onlyFor('index');
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
