<?php

namespace AdamJedlicka\Luna\Http\Requests;

use AdamJedlicka\Luna\Resource;
use AdamJedlicka\Luna\Facades\Resources;
use AdamJedlicka\Luna\Traits\Authorizes;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    use Authorizes;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $model = $this->resource()::$model;

        return $this->authorizeIfPolicyExists('viewAny', $model);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
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
