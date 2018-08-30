<?php

namespace AdamJedlicka\Admin\Traits;

trait Authorizes
{
    /**
     * Checks for authorization only if policy exists. If not allows the action.
     *
     * @param string $policy Name of the policy method
     * @param \Illuminate\Database\Eloquent\Model|string $model
     * @return bool
     */
    public function authorizeIfPolicyExists(string $policy, $model, ...$args) : bool
    {
        $policyClass = policy($model);

        if ($policyClass && method_exists($policyClass, $policy)) {
            return auth()->user()->can($policy, array_merge([$model], $args));
        }

        return true;
    }
}
