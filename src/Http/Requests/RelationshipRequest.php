<?php

namespace AdamJedlicka\Admin\Http\Requests;

use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Facades\Resources;

class RelationshipRequest extends IndexRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $relatedModel = $this->relatedResource()::$model;

        return parent::authorize() && $this->authorizeIfPolicyExists('viewAny', $relatedModel);
    }

    /**
     * Returns resource for current request
     *
     * @return \AdamJedlicka\Admin\Resource
     */
    public function resource() : Resource
    {
        $resource = Resources::forName($this->resource);
        $model = $resource::$model::findOrFail($this->resourceKey);
        $resource->setModel($model);

        return $resource;
    }

    /**
     * Returns relationship for current request
     *
     * @return \AdamJedlicka\Admin\Fields\HasMany
     */
    public function relationship()
    {
        $model = $this->resource()->getModel();
        return $model->{$this->relationship}();
    }

    /**
     * Returns related resource for current request
     *
     * @return \AdamJedlicka\Admin\Resource
     */
    public function relatedResource() : Resource
    {
        $relatedModel = $this->relationship()->getRelated();

        return Resources::forModel($relatedModel);
    }
}
