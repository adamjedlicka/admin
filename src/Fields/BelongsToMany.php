<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\FieldCollection;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Facades\ResourceService;

class BelongsToMany extends RelationshipField
{
    protected $visibleOn = ['detail'];

    protected $panel = true;

    protected $fields = [];

    public function exports(Resource $resource)
    {
        return [
            'relatedResourceName' => $this->relatedResource->name(),
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
            ->each(function (Field $field) {
                $field->isPivot();
            });
    }

    public function getRelatedPivotKeyName() : string
    {
        return $this->relationship->getRelatedPivotKeyName();
    }
}
