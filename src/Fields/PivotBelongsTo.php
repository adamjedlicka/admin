<?php

namespace AdamJedlicka\Admin\Fields;

use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;

class PivotBelongsTo extends BelongsTo
{
    public function retrieve(Model $model)
    {
        return $model->{$model->getRelatedKey()};
    }

    public function meta(Resource $resource, Model $model)
    {
        return [
            'title' => $resource->title(),
        ];
    }

    public function getType() : string
    {
        return 'BelongsTo';
    }

    public function getForeignKeyName() : string
    {
        return $this->relationship->getRelatedPivotKeyName();
    }
}
