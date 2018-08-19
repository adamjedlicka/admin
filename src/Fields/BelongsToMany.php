<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

class BelongsToMany extends Field
{
    protected $visibleOn = ['detail'];

    protected $panel = true;

    /**
     * @var \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected $relationship;

    /**
     * @var \AdamJedlicka\Admin\Fields\BelongsToMany
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
            'relatedName' => $this->relatedResource->name(),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
