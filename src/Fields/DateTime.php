<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Database\Eloquent\Model;

class DateTime extends Field
{
    protected $indexVisible = true;

    protected function resolveAttribute(Model $model)
    {
        return $model->getAttribute($this->field)->toDateTimeString();
    }
}
