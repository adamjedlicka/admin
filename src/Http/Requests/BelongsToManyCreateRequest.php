<?php

namespace AdamJedlicka\Admin\Http\Requests;

use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Facades\Resources;

class BelongsToManyCreateRequest extends RelationshipRequest
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

        return parent::authorize() && $this->authorizeIfPolicyExists("attachAny$name", $model);
    }
}
