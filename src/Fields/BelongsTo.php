<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\ResourceSerializer;
use AdamJedlicka\Admin\Serializers\IndexSerializer;
use function AdamJedlicka\Admin\Support\get_resource_from_model;

class BelongsTo extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    public function retrieve(Model $model)
    {
        return $model->getAttribute($this->name)->getKey();
    }

    public function persist(Model $model, $value)
    {
        $foreignKey = call_user_func([$model, $this->getName()])->getForeignKey();
        $model->setAttribute($foreignKey, $value);
    }

    protected function meta(Model $model)
    {
        $belongsToModel = $model->{$this->getName()};
        $belongsToResource = get_resource_from_model($belongsToModel);

        return [
            'title' => $belongsToResource->title(),
            'key' => $belongsToResource->key(),
            'name' => Str::lower($belongsToResource->name()),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
