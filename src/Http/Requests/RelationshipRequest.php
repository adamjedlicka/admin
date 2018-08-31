<?php

namespace AdamJedlicka\Luna\Http\Requests;

use AdamJedlicka\Luna\Resource;
use AdamJedlicka\Luna\Facades\Resources;

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
     * @return \AdamJedlicka\Luna\Resource
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
     * @return \AdamJedlicka\Luna\Fields\HasMany
     */
    public function relationship()
    {
        $model = $this->resource()->getModel();
        return $model->{$this->relationship}();
    }

    /**
     * Returns related resource for current request
     *
     * @return \AdamJedlicka\Luna\Resource
     */
    public function relatedResource() : Resource
    {
        $relatedModel = $this->relationship()->getRelated();

        return Resources::forModel($relatedModel);
    }
}
