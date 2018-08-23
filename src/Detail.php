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

    public function __construct(FieldCollection $fields, $model)
    {
        $this->fields = $fields;
        $this->model = $model;
    }

    protected function data()
    {
        return $this->model->only($this->fields->names());
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
        ];
    }
}
