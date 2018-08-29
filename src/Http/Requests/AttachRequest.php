<?php

namespace AdamJedlicka\Admin\Http\Requests;

class AttachRequest extends RelationshipRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $model = $this->resource()->getModel();
        $name = $this->relatedResource()->name();

        return parent::authorize() && $this->authorizeIfPolicyExists("attach$name", $model);
    }
}
