<?php

namespace AdamJedlicka\Admin\Fields;

use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

class PivotBelongsTo extends Field
{
    public function getType() : string
    {
        return 'BelongsTo';
    }

    public function meta(Resource $resource)
    {
        return [
            'name' => $this->name,
        ];
    }

    public function value(Resource $resource, Model $model)
    {
        $relatedResource = ResourceService::getResourceFromModel($model);

        return [
            'title' => $relatedResource->title(),
            'key' => $model->getKey(),
        ];
    }
}
