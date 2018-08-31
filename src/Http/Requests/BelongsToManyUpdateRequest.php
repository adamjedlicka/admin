<?php

namespace AdamJedlicka\Luna\Http\Requests;

use AdamJedlicka\Luna\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Luna\Facades\Resources;

class BelongsToManyUpdateRequest extends RelationshipRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $name = $this->relatedResource()->name();
        $model = $this->resource()->getModel();

        return parent::authorize() && $this->authorizeIfPolicyExists("update$name", $model, $this->relatedModel());
    }

    public function relatedModel() : Model
    {
        return $this->relationship()->find($this->relationshipKey);
    }

    /**
     * Returns related resource for current request
     *
     * @return \AdamJedlicka\Luna\Resource
     */
    public function relatedResource() : Resource
    {
        return Resources::forModel($this->relatedModel());
    }
}
