<?php

namespace AdamJedlicka\Luna\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Luna\Resource;
use AdamJedlicka\Luna\Fields\Field;
use AdamJedlicka\Luna\FieldCollection;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Luna\Traits\Authorizes;

class BelongsToMany extends RelationshipField
{
    use Authorizes;

    protected $visibleOn = ['detail'];

    protected $panel = true;

    protected $fields = [];

    public function exports(Resource $resource)
    {
        return [
            'relatedResourceName' => $this->relatedResource->name(),
            'policies' => $this->getPolicies(),
        ];
    }

    public function fields(array $fields) : self
    {
        $this->fields = $fields;

        return $this;
    }

    public function getFields() : FieldCollection
    {
        return (new FieldCollection($this->fields))
            ->map(function (Field $field) {
                return $field->isPivot();
            });
    }

    /**
     * Returns computed array of all creation rules
     *
     * @return array
     */
    public function getPivotCreationRules() : array
    {
        return $this->getFields()
            ->mapWithKeys(function (Field $field) {
                return [$field->getName() => $field->getCreationRules()];
            })
            ->toArray();
    }

    /**
     * Returns computed array of all update rules
     *
     * @return array
     */
    public function getPivotUpdateRules() : array
    {
        return $this->getFields()
            ->mapWithKeys(function (Field $field) {
                return [$field->getName() => $field->getUpdateRules()];
            })
            ->toArray();
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
                return $field instanceof BelongsToMany
                    && $field->getRelatedPivotKeyName() == $this->relationship->getForeignPivotKeyName();
            })
            ->first();
    }

    public function getForeignKeyName() : string
    {
        return $this->relationship->getForeignPivotKeyName();
    }

    public function getRelatedPivotKeyName() : string
    {
        return $this->relationship->getRelatedPivotKeyName();
    }

    public function getRelationName() : string
    {
        return $this->relationship->getRelationName();
    }

    public function getPolicies()
    {
        $name = $this->relatedResource->name();
        $model = $this->resource->getModel();

        return [
            'attachAny' => $this->authorizeIfPolicyExists("attachAny$name", $model),
        ];
    }
}
