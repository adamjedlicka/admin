<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\ResourceSerializer;
use AdamJedlicka\Admin\Serializers\IndexSerializer;

class BelongsTo extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    protected function meta()
    {
        $resource = get_resource_from_name($this->name);
        $models = $resource->query()->get();

        return $models->map(function ($model) {
            $resource = get_resource_from_name($this->name);
            $resource->setModel($model);

            return (new ResourceSerializer($resource))
                ->only('title', 'key');
        });
    }

    protected function resolveAttribute(Model $model)
    {
        $resource = get_resource_from_name($this->name);
        $resource->setModel($model->getAttribute($this->name));

        return (new ResourceSerializer($resource))
            ->only('title', 'key');
    }

    public function persist(Model $model, $value)
    {
        $foreignKey = call_user_func([$model, $this->getName()])->getForeignKey();
        $model->setAttribute($foreignKey, $value['key']);
    }
}
