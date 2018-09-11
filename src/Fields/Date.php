<?php

namespace AdamJedlicka\Luna\Fields;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Date extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    /**
     * Returns formated value for API to use
     * Uses Y-m-d format
     *
     * @param mixed $value
     * @return string
     */
    protected function formatValue($value)
    {
        if (is_null($value)) return null;

        if ($value instanceof Carbon) {
            return $value->format('Y-m-d');
        } else {
            return (new Carbon($value))->format('Y-m-d');
        }
    }

    /**
     * Retrieves the model from the database
     *
     * @param Model $model Coresponding model
     * @return mixed
     */
    public function retrieve(Model $model)
    {
        return $this->formatValue(
            $model->getAttribute($this->name)
        );
    }

    /**
     * Default value getter
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->formatValue(
            $this->default
        );
    }
}
