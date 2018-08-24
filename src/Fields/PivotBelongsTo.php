<?php

namespace AdamJedlicka\Admin\Fields;

use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

class PivotBelongsTo extends Field
{
    /**
     * @var string
     */
    protected $relationship;

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
            'source' => "/api/relationships/{$resource->name()}/belongsTo/{$this->relationship}",
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


    /**
     * Sets the relationship
     *
     * @param string $relationship
     * @return self
     */
    public function relationship(string $relationship) : self
    {
        $this->relationship = $relationship;

        return $this;
    }
}
