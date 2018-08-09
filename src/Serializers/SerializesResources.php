<?php

namespace AdamJedlicka\Admin\Serializers;

trait SerializesResources
{
    public function fillComputedFields($model)
    {
        foreach ($this->resource->computedFields() as $field) {
            $result = call_user_func($field->getCallable(), $model);
            $model->setAttribute($field->getField(), $result);
        }
    }
}
