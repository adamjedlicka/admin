<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\FieldCollection;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Admin\Traits\Authorizes;
use AdamJedlicka\Admin\Facades\ResourceService;

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

    public function getRelatedPivotKeyName() : string
    {
        return $this->relationship->getRelatedPivotKeyName();
    }

    public function getPolicies()
    {
        $name = $this->relatedResource->name();
        $model = $this->resource->getModel();

        return [
            'attach' => $this->authorizeIfPolicyExists("attach$name", $model),
        ];
    }
}
