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

    /**
     * @var \AdamJedlicka\Admin\Fields\BelongsTo
     */
    protected $relatedField;

    protected function prepare(Resource $resource, Model $model)
    {
        $this->relationship = $model->{$this->name}();

        $this->relatedResource = ResourceService::getResourceFromModel(
            $this->relationship->getRelated()
        );

        $this->relatedField = $this->relatedResource->getFields()
            ->filter(function (Field $field) {
                return $field instanceof BelongsTo;
            })
            ->filter(function (BelongsTo $field) {
                return $this->relationship->getForeignKeyName() == $field->getForeignKey();
            })
            ->first();
    }

    protected function metaInfo(Resource $resource)
    {
        return [
            'relatedName' => $this->relatedResource->name(),
            'relatedFieldName' => $this->relatedField->getName(),
        ];
    }

    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }

    public function getRelatedField() : Field
    {
        return $this->relatedField;
    }
}
