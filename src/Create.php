<?php

namespace AdamJedlicka\Admin;

use AdamJedlicka\Admin\FieldCollection;
use Illuminate\Contracts\Support\Responsable;

class Create implements Responsable
{
    /**
     * @var \AdamJedlicka\Admin\FieldCollection
     */
    protected $fields;

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var array
     */
    protected $links = [];

    public function __construct(FieldCollection $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Sets title of the detail page
     *
     * @param string $title
     * @return self
     */
    public function title(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Sets the template for generation of storeUrl
     *
     * @param string $storeUrl
     * @return self
     */
    public function storeUrl(string $storeUrl) : self
    {
        $this->links['store'] = $storeUrl;

        return $this;
    }

    protected function data()
    {
        return $this->fields
            ->mapWithKeys(function ($field) {
                return [$field->getName() => null];
            });
    }

    public function toResponse($request)
    {
        return [
            'fields' => $this->fields,
            'data' => $this->data(),
            'title' => $this->title,
            'links' => $this->links,
        ];
    }
}
