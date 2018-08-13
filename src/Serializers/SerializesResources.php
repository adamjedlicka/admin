<?php

namespace AdamJedlicka\Admin\Serializers;

use AdamJedlicka\Admin\Fields\Field;

trait SerializesResources
{
    protected function fillComputedFields($model)
    {
        foreach ($this->resource->computedFields() as $field) {
            $result = call_user_func($field->getCallable(), $model);
            $model->setAttribute($field->getField(), $result);
        }
    }

    protected function onlyFieldsOn(string $view)
    {
        return collect($this->resource->fields())
            ->filter(function (Field $field) use ($view) {
                return $field->isVisibleOn($view);
            })
            ->values();
    }

    protected function onlyFieldNamesOn(string $view)
    {
        return $this->onlyFieldsOn($view)
            ->map(function (Field $field) {
                return $field->getField();
            })
            ->toArray();
    }
}
