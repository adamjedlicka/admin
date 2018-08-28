<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\Resources;

class HasOne extends RelationshipField
{
    protected $visibleOn = ['index', 'detail'];

    public function retrieve(Model $model)
    {
        return optional($model->{$this->getName()})->getKey();
    }

    public function exports(Resource $resource)
    {
        return [
            'resource' => $this->relatedResource->name(),
            'relatedFieldName' => $this->getRelatedField()->getName(),
        ];
    }

    public function meta(Resource $resource, Model $model)
    {
        $relatedModel = $model->{$this->getName()};

        if ($relatedModel) {
            $this->relatedResource->setModel($relatedModel);
            $title = $this->relatedResource->title();
        }

        return [
            'title' => $title ?? null,
        ];
    }
}
