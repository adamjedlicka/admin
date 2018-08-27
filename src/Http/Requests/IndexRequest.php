<?php

namespace AdamJedlicka\Admin\Http\Requests;

use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Facades\Resources;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
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
     * Checks for authorization only if policy exists. If not allows the action.
     *
     * @param string $policy Name of the policy method
     * @param \Illuminate\Database\Eloquent\Model|string $model
     * @return bool
     */
    public function authorizeIfPolicyExists(string $policy, $model) : bool
    {
        $policyClass = policy($model);

        if ($policyClass && method_exists($policyClass, $policy)) {
            return auth()->user()->can($policy, $model);
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
