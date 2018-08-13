<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Database\Eloquent\Model;

class DateTime extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    protected function resolveAttribute(Model $model)
    {
        return $model->getAttribute($this->field)->toDateTimeString();
    }
}
