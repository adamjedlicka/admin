<?php

namespace AdamJedlicka\Admin\Http\Requests;

use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Facades\Resources;

class DeleteRequest extends IndexRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $model = $this->resource()->getModel();

        return parent::authorize() && $this->authorizeIfPolicyExists('delete', $model);
    }

    /**
     * Returns resource for current request
     *
     * @return \AdamJedlicka\Admin\Resource
     */
    public function resource() : Resource
    {
        $resource = Resources::forName($this->resource);
        $model = $resource::$model::findOrFail($this->key);
        $resource->setModel($model);

        return $resource;
    }
}
