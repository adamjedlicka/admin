<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

class BelongsTo extends Field
{
    protected $visibleOn = ['index', 'detail', 'edit'];

    /**
     * @var \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected $relationship;

    /**
     * @var \AdamJedlicka\Admin\Resource
     */
    protected $relatedResource;

    /**
     * @var string
     */
    protected $foreignKey;

    protected function prepare(Resource $resource, Model $model)
    {
        $this->relationship = $model->{$this->name}();
        $this->foreignKey = $this->relationship->getForeignKey();
        $this->relatedResource = ResourceService::getResourceFromModel(
            $model->{$this->name} ?? $this->relationship->getRelated()
        );
    }

    public function retrieve(Model $model)
    {
        return $model->getAttribute($this->name)->getKey();
    }

    public function persist(Model $model, $value)
    {
        $model->setAttribute($this->foreignKey, $value);
    }

    protected function metaInfo(Resource $resource)
    {
        $name = Str::lower($this->relatedResource->name());

        return [
            'name' => $name,
            'source' => "/api/resources/$name",
        ];
    }

    protected function metaValue(Resource $resource, Model $model)
    {
        return [
            'title' => $this->relatedResource->title(),
            'key' => $this->relatedResource->key(),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }

    public function getForeignKey()
    {
        return $this->foreignKey;
    }
}
