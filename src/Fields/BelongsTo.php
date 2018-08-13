<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Database\Eloquent\Model;


class BelongsTo extends Field
{
    protected $visibleOn = ['detail', 'edit'];

    protected function resolveAttribute(Model $model)
    {
        return [
            'name' => $this->field,
        ];
    }
}
