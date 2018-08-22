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

    /**
     * @var array
     */
    protected $fields = [];

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

    public function fields(array $fields) : self
    {
        $this->fields = $fields;

        foreach ($this->fields as $field) {
            $field->options(function ($model) use ($field) {
                return $model->pivot->{$field->getName()};
            });
        }

        return $this;
    }

    public function getFields() : array
    {
        return $this->fields;
    }
}
