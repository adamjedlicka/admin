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

    public function retrieve(Model $model)
    {
        return $model->getKey();
    }

    public function meta(Resource $resource)
    {
        $relatedResource = ResourceService::getResourceFromName($this->getDisplayName());

        return [
            'name' => $relatedResource->name(),
        ];
    }

    public function value(Model $model)
    {
        $relatedResource = ResourceService::getResourceFromModel($model);

        return [
            'title' => $relatedResource->title(),
            'key' => $model->getKey(),
        ];
    }
}
