<?php

namespace AdamJedlicka\Admin\Http\Requests;

class BelongsToManyDeleteRequest extends RelationshipRequest
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
        $relatedModel = $this->relationship()->getRelated();

        return parent::authorize()
            && $this->authorizeIfPolicyExists("detach$name", $model, $relatedModel);
    }
}
