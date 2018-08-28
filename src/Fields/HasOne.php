<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\Resources;

class HasOne extends Field
{
    protected $visibleOn = ['index', 'detail'];

    public function retrieve(Model $model)
    {
        return optional($model->{$this->getName()})->getKey();
    }

    public function persist(Model $model, $value)
    {
        //
    }

    public function exports(Resource $resource)
    {
        $relationship = $resource->newModel()->{$this->getName()}();
        $relatedModel = $relationship->getRelated();
        $relatedResource = Resources::forModel($relatedModel);

        return [
            'resource' => $relatedResource->name(),
            'relatedFieldName' => $this->getRelatedField($resource)->getName(),
        ];
    }

    public function meta(Resource $resource, Model $model)
    {
        $relatedModel = $model->{$this->getName()};

        if ($relatedModel) {
            $title = Resources::forModel($relatedModel)->title();
        }

        return [
            'title' => $title ?? null,
        ];
    }

    public function getRelatedField(Resource $resource)
    {
        $model = $resource->newModel();
        $relationship = $model->{$this->getName()}();
        $foreignKeyName = $relationship->getForeignKeyName();

        $relatedModel = $relationship->getRelated();
        $relatedResource = Resources::forModel($relatedModel);

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
