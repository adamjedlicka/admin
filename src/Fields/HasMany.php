<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

class HasMany extends Field
{
    protected $visibleOn = ['detail'];

    protected $panel = true;

    public function meta(Resource $resource)
    {
        $model = $resource->model()::make();
        $relationship = $model->{$this->getName()}();

        $relatedModel = $relationship->getRelated();
        $relatedResource = ResourceService::getResourceFromModel($relatedModel);

        return [
            'relatedName' => $relatedResource->name(),
            // 'relatedFieldName' => $relatedField->getName(),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
