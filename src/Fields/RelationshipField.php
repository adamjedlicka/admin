<?php

namespace AdamJedlicka\Luna\Fields;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use AdamJedlicka\Luna\Facades\Resources;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class RelationshipField extends Field
{
    /**
     * @var mixed
     */
    protected $relationship;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $relatedModel;

    /**
     * @var \AdamJedlicka\Luna\Resource
     */
    protected $relatedResource;

    /**
     * @var \AdamJedlicka\Luna\Fields\Field
     */
    protected $relatedField;

    protected function __construct(...$args)
    {
        parent::__construct(...$args);

        $model = $this->resource->newModel();

        $this->relationship = $model->{$this->getName()}();
        $this->relatedModel = $this->relationship->getRelated();
        $this->relatedResource = Resources::forModel($this->relatedModel);
    }

    /**
     * Returns related field
     *
     * @return \AdamJedlicka\Luna\Fields\Field
     */
    public function getRelatedField() : Field
    {
        return collect($this->relatedResource->fields())
            ->filter(function ($field) {
                return $field instanceof RelationshipField
                    && $field->getForeignKeyName() == $this->getForeignKeyName();
            })
            ->first();
    }

    /**
     * Returns name of the foreign key
     *
     * @return string
     */
    public function getForeignKeyName() : string
    {
        return $this->relationship->getForeignKeyName();
    }

    /**
     * Class methods use camel case instead of snake case
     * and relationship fields do not use databse fields but relationship methods on models.
     *
     * @param string $displayName
     * @return string
     */
    protected function resolveName(string $displayName) : string
    {
        return Str::camel($displayName);
    }
}
