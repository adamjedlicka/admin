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

    /**
     * @var \AdamJedlicka\Admin\Fields\HasMany
     */
    protected $relationship;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $relatedResource;

    protected function prepare(Resource $resource, Model $model)
    {
        $this->relationship = $model->{$this->name}();

        $this->relatedResource = ResourceService::getResourceFromModel(
            $this->relationship->getRelated()
        );
    }

    protected function metaInfo(Resource $resource)
    {
        return [
            'fields' => $this->relatedResource->getFields('detail'),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
