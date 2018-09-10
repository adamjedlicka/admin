<?php

namespace AdamJedlicka\Luna\Fields;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class DateTime extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    /**
     * Retrieves the model from the database
     *
     * @param Model $model Coresponding model
     * @return mixed
     */
    public function retrieve(Model $model)
    {
        return (new Carbon($model->getAttribute($this->name)))
            ->toIso8601String();
    }

    /**
     * Default value getter
     *
     * @return mixed
     */
    public function getDefault()
    {
        return (new Carbon($this->default))
            ->toIso8601String();
    }
}
