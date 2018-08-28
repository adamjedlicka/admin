<?php

namespace AdamJedlicka\Admin\Http\Requests;

use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Facades\Resources;

class CreateRequest extends IndexRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $model = $this->resource()::$model;

        return parent::authorize() && $this->authorizeIfPolicyExists('create', $model);
    }

    /**
     * Returns resource for current request
     *
     * @return \AdamJedlicka\Admin\Resource
     */
    public function resource() : Resource
    {
        return Resources::forName($this->resource);
    }
}
