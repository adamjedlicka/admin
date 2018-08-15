<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
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

    protected function meta(Resource $resource)
    {
        if (!$resource->getModel()) return;

        $belongsToModel = $resource->getModel()->{$this->getName()};
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
