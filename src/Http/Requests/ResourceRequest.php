<?php

namespace AdamJedlicka\Admin\Http\Requests;

use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Facades\Resources;
use Illuminate\Foundation\Http\FormRequest;

class ResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $policy = policy($this->resource()::$model);

        if ($policy && method_exists($policy, 'viewAny')) {
            return auth()->user()->can('viewAny', $this->resource()::$model);
        }

        return true;
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
     * @return \AdamJedlicka\Admin\Resource
     */
    public function resource() : Resource
    {
        return Resources::forName($this->resource);
    }
}
