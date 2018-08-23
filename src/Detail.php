<?php

namespace AdamJedlicka\Admin;

use Illuminate\Contracts\Support\Responsable;

class Detail implements Responsable
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

    public function __construct(FieldCollection $fields, $model)
    {
        $this->fields = $fields;
        $this->model = $model;
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
     * Sets the template for generation of editUrl
     *
     * @param string $editUrl
     * @return self
     */
    public function editUrl(string $editUrl) : self
    {
        $this->links['edit'] = $editUrl;

        return $this;
    }

    /**
     * Sets the template for generation of deleteUrl
     *
     * @param string $deleteUrl
     * @return self
     */
    public function deleteUrl(string $deleteUrl) : self
    {
        $this->links['delete'] = $deleteUrl;

        return $this;
    }

    protected function data()
    {
        return $this->fields
            ->each(function ($field) {
                $field->setModel($this->model);
            })
            ->mapWithKeys(function ($field) {
                return [$field->getName() => $field->retrieve($this->model)];
            });
    }

    protected function meta()
    {
        return $this->fields
            ->each(function ($field) {
                $field->setModel($this->model);
            })
            ->mapWithKeys(function ($field) {
                return [$field->getName() => $field->value($this->model)];
            });
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return [
            'fields' => $this->fields,
            'data' => $this->data(),
            'meta' => $this->meta(),
            'title' => $this->title,
            'links' => $this->links,
        ];
    }
}
