<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

class HasOne extends Field
{
    protected $visibleOn = ['index', 'detail'];

    public function retrieve(Model $model)
    {
        return optional($model->{$this->getName()})->getKey();
    }

    public function value(Model $model)
    {
        $title = '';

        $relatedModel = $model->{$this->getName()};

        if ($relatedModel) {
            $title = ResourceService::getResourceFromModel($relatedModel)->title();
        }

        return [
            'title' => $title,
        ];
    }

    public function meta(Resource $resource)
    {
        $relationship = $resource->model()::make()->{$this->getName()}();
        $relatedModel = $relationship->getRelated();
        $relatedResource = ResourceService::getResourceFromModel($relatedModel);

        return [
            'resource' => $relatedResource->name(),
        ];
    }

    public function getRelatedField(Resource $resource)
    {
        $model = $resource->model()::make();
        $relationship = $model->{$this->getName()}();
        $foreignKeyName = $relationship->getForeignKeyName();

        $relatedModel = $relationship->getRelated();
        $relatedResource = ResourceService::getResourceFromModel($relatedModel);

        return collect($relatedResource->fields())
            ->filter(function ($field) {
                return $field instanceof BelongsTo;
            })
            ->filter(function ($field) use ($relatedModel, $foreignKeyName) {
                return $relatedModel->{$field->getName()}()->getForeignKey() == $foreignKeyName;
            })
            ->first();
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
