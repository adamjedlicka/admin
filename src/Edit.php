<?php

namespace AdamJedlicka\Admin;

use Illuminate\Contracts\Support\Responsable;

class Edit implements Responsable
{
    /**
     * @var \AdamJedlicka\Admin\FieldCollection
     */
    protected $fields;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var array
     */
    protected $links = [];

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
     * Sets the template for generation of updateUrl
     *
     * @param string $updateUrl
     * @return self
     */
    public function updateUrl(string $updateUrl) : self
    {
        $this->links['update'] = $updateUrl;

        return $this;
    }

    public function __construct(FieldCollection $fields, $model)
    {
        $this->fields = $fields;
        $this->model = $model;
    }

    protected function data()
    {
        return $this->fields
            ->mapWithKeys(function ($field) {
                return [$field->getName() => $field->retrieve($this->model)];
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
