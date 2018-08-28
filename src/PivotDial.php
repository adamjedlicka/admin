<?php

namespace AdamJedlicka\Admin;

use AdamJedlicka\Admin\Fields\Id;
use AdamJedlicka\Admin\Fields\Text;
use AdamJedlicka\Admin\Fields\Field;
use AdamJedlicka\Admin\FieldCollection;
use AdamJedlicka\Admin\Fields\BelongsTo;

class PivotDial extends Dial
{
    protected $pivotFields = [];

    /**
     * Sets pivot fields
     */
    public function withPivot($fields) : self
    {
        $this->pivotFields = $fields;

        return $this;
    }

    protected function paginated()
    {
        [$data, $pagination] = parent::paginated();

        $data = $data->each(function (Resource $resource) {
            $resource->extraFields($this->pivotFields);
        });

        return [$data, $pagination];
    }

    protected function fields() : FieldCollection
    {
        return parent::fields()
            ->filter(function (Field $field) {
                return $field instanceof Id;
            })
            ->merge($this->pivotFields)
            ->values();
    }
}
