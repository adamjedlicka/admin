<?php

namespace AdamJedlicka\Luna\Http\Requests;

use AdamJedlicka\Luna\Resource;
use AdamJedlicka\Luna\Facades\Resources;

class StoreReqeust extends IndexRequest
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
     * @return \AdamJedlicka\Luna\Resource
     */
    public function resource() : Resource
    {
        return Resources::forName($this->resource);
    }
}
