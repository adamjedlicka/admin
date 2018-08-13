<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Database\Eloquent\Model;

class DateTime extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    protected function resolveAttribute(Model $model)
    {
        $date = $model->getAttribute($this->name);

        return $date ? $date->toDateTimeString() : null;
    }
}
