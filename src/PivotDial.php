<?php

namespace AdamJedlicka\Admin;

use AdamJedlicka\Admin\Fields\Id;
use AdamJedlicka\Admin\Fields\Text;
use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\FieldCollection;
use AdamJedlicka\Admin\Fields\BelongsTo;
use AdamJedlicka\Admin\Facades\Resources;
use Illuminate\Database\Eloquent\Builder;
use AdamJedlicka\Admin\Fields\PivotBelongsTo;

class PivotDial extends Dial
{
    /**
     * @var \AdamJedlicka\Admin\FieldCollection
     */
    protected $pivotFields;

    public function __construct(Resource $resource, $relationship)
    {
        parent::__construct($resource);

        $this->query = $relationship;

        $relatedResource = Resources::forModel($this->query->getRelated());

        $this->pivotFields = new FieldCollection([

            PivotBelongsTo::make($relatedResource->name(), $this->query->getRelationName(), $this->resource)
                ->isPivot(),

        ]);
    }

    /**
     * Sets pivot fields
     */
    public function withPivot($fields) : self
    {
        $this->pivotFields = $this->pivotFields->merge($fields);

        return $this;
    }

    protected function paginated()
    {
        [$data, $pagination] = parent::paginated();

        $data = $data->map(function (Resource $resource) {
            return $resource->extraFields($this->fields());
        });

        return [$data, $pagination];
    }

    protected function fields() : FieldCollection
    {
        return $this->pivotFields;
    }
}
