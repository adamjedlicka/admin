<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\Resources;

class HasMany extends Field
{
    protected $visibleOn = ['detail'];

    protected $panel = true;

    public function exports(Resource $resource)
    {
        $model = $resource->newModel();
        $relationship = $model->{$this->getName()}();
        $foreignKeyName = $relationship->getForeignKeyName();

        $relatedModel = $relationship->getRelated();
        $relatedResource = Resources::forModel($relatedModel);
        $relatedField = $this->getRelatedField($resource);

        return [
            'relatedResourceName' => $relatedResource->name(),
            'relatedFieldName' => $relatedField->getName(),
        ];
    }

    public function getRelatedField(Resource $resource)
    {
        $model = $resource::$model::make();
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
