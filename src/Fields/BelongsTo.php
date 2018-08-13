<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Serializers\IndexSerializer;

class BelongsTo extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    protected function meta()
    {
        return new IndexSerializer(get_resource_from_name($this->name));
    }

    protected function resolveAttribute(Model $model)
    {
        return get_resource_from_name($this->name, $model->getAttribute($this->name));
    }

    public function persist(Model $model, $value)
    {
        $foreignKey = call_user_func([$model, $this->getName()])->getForeignKey();
        $model->setAttribute($foreignKey, $value['key']);
    }
}
