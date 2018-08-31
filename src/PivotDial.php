<?php

namespace AdamJedlicka\Luna;

use AdamJedlicka\Luna\Fields\Id;
use AdamJedlicka\Luna\Fields\Text;
use AdamJedlicka\Luna\Fields\Field;
use AdamJedlicka\Luna\FieldCollection;
use AdamJedlicka\Luna\Fields\BelongsTo;
use AdamJedlicka\Luna\Facades\Resources;
use Illuminate\Database\Eloquent\Builder;
use AdamJedlicka\Luna\Fields\PivotBelongsTo;

class PivotDial extends Dial
{
    /**
     * @var \AdamJedlicka\Luna\FieldCollection
     */
    protected $pivotFields;

    public function __construct(Resource $resource, $relationship)
    {
        parent::__construct($resource);

        $this->query = $relationship;

        $relatedResource = Resources::forModel($this->query->getRelated());

        $this->pivotFields = new FieldCollection([

            PivotBelongsTo::make($relatedResource->name(), $this->query->getRelationName(), $this->resource)
                ->sortable()
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
            $resource = $resource->extraFields($this->fields());

            return PivotResource::fromResource($resource, $this->resource, $this->query->getRelationName());
        });

        return [$data, $pagination];
    }

    protected function fields() : FieldCollection
    {
        return $this->pivotFields;
    }
}
