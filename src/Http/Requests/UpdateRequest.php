<?php

namespace AdamJedlicka\Luna\Http\Requests;

use AdamJedlicka\Luna\Resource;
use AdamJedlicka\Luna\Facades\Resources;

class UpdateRequest extends IndexRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $model = $this->resource()->getModel();

        return parent::authorize() && $this->authorizeIfPolicyExists('update', $model);
    }

    /**
     * Returns resource for current request
     *
     * @return \AdamJedlicka\Luna\Resource
     */
    public function resource() : Resource
    {
        $resource = Resources::forName($this->resource);
        $model = $resource::$model::findOrFail($this->resourceKey);
        $resource->setModel($model);

        return $resource;
    }
}
