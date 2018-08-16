<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

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

    protected function metaInfo(Resource $resource)
    {
        $modelName = $resource->fullyQualifiedModelName();
        $model = new $modelName;
        $belongsToModel = call_user_func([$model, $this->name])->getRelated();
        $belongsToResource = ResourceService::getResourceFromModel($belongsToModel);

        $name = Str::lower($belongsToResource->name());

        return [
            'name' => $name,
            'source' => "/api/resources/$name",
        ];
    }

    protected function metaValue(Resource $resource, Model $model)
    {
        $belongsToModel = $model->{$this->getName()};
        $belongsToResource = ResourceService::getResourceFromModel($belongsToModel);

        return [
            'title' => $belongsToResource->title(),
            'key' => $belongsToResource->key(),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }

    public function getForeignKey()
    {
        $modelName = $this->resource->fullyQualifiedModelName();
        $model = new $modelName;
        return call_user_func([$model, $this->name])->getForeignKey();
    }
}
