<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\Resources;

class BelongsTo extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    public function retrieve(Model $model)
    {
        return optional($model->getAttribute($this->name))->getKey();
    }

    public function persist(Model $model, $value)
    {
        $foreignKey = $model->{$this->getName()}()->getForeignKey();

        $model->setAttribute($foreignKey, $value);
    }

    public function exports(Resource $resource)
    {
        $model = $resource->newModel();
        $relationship = $model->{$this->getName()}();
        $relatedModel = $relationship->getRelated();
        $relatedResource = Resources::forModel($relatedModel);

        return [
            'relatedResourceName' => $relatedResource->name(),
        ];
    }

    public function meta(Resource $resource, Model $model)
    {
        $relatedModel = $model->{$this->getName()};

        if ($relatedModel) {
            $relatedResource = Resources::forModel($relatedModel);
            $title = $relatedResource->title();
        }

        return [
            'title' => $title ?? null,
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
