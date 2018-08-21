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

    public function boot(Resource $resource)
    {
        $modelName = $resource->model();
        $model = new $modelName;

        $this->relationship = $model->{$this->name}();

        $this->foreignKey = $this->relationship->getForeignKey();

        $this->relatedResource = ResourceService::getResourceFromModel(
            $this->relationship->getRelated()
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

    public function meta(Resource $resource)
    {
        $name = $this->relatedResource->name();

        return [
            'name' => $name,
            'source' => "/api/resources/$name",
        ];
    }

    public function value(Resource $resource, Model $model)
    {
        $relatedModel = $model->{$this->name};
        $relatedResource = ResourceService::getResourceFromModel($relatedModel);

        return [
            'title' => $relatedResource->title(),
            'key' => $relatedModel->getKey(),
        ];
    }

    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
