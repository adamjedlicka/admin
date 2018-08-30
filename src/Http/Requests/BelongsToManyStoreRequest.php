<?php

namespace AdamJedlicka\Admin\Http\Requests;

class BelongsToManyStoreRequest extends RelationshipRequest
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
        $relatedModel = $this->relatedResource()::$model::findOrFail($this->get($this->relationship));

        return parent::authorize() && $this->authorizeIfPolicyExists("attach$name", $model, $relatedModel);
    }
}
