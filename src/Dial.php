<?php

namespace AdamJedlicka\Admin;

use Illuminate\Contracts\Support\Responsable;

class Dial implements Responsable
{
    /**
     * @var \AdamJedlicka\Admin\FieldCollection
     */
    protected $fields;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $links = [];

    public function __construct(FieldCollection $fields, $query)
    {
        $this->fields = $fields;
        $this->query = $query;
    }

    /**
     * Sets the template for generation of detailUrl
     *
     * @param string $detailUrl
     * @return self
     */
    public function detailUrl(string $detailUrl) : self
    {
        $this->links['detail'] = $detailUrl;

        return $this;
    }

    protected function data()
    {
        $this->query->select($this->fields->names());

        return $this->paginated();
    }

    protected function paginated()
    {
        $paginated = $this->query->simplePaginate();

        $data = $paginated->items();

        $pagination = [
            'currentPage' => $paginated->currentPage(),
            'hasNextPage' => $paginated->nextPageUrl() != null,
            'hasPreviousPage' => $paginated->previousPageUrl() != null,
        ];

        return [$data, $pagination];
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        [$data, $pagination] = $this->data();

        return [
            'fields' => $this->fields,
            'data' => $data,
            'pagination' => $pagination,
            'links' => $this->links,
        ];
    }
}
