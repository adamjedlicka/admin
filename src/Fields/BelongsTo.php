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
        $foreignKey = $model->{$this->getName()}()->getForeignKey();

        $model->setAttribute($foreignKey, $value);
    }

    public function meta(Resource $resource)
    {
        $model = $resource->model()::make();
        $relationship = $model->{$this->getName()}();
        $relatedModel = $relationship->getRelated();
        $relatedResource = ResourceService::getResourceFromModel($relatedModel);

        return [
            'name' => $relatedResource->name(),
            'source' => "/api/relationships/{$resource->name()}/belongsTo/{$this->getName()}",
        ];
    }

    public function value(Model $model)
    {
        $relatedModel = $model->{$this->getName()};
        $relatedResource = ResourceService::getResourceFromModel($relatedModel);

        return [
            'title' => $relatedResource->title(),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
