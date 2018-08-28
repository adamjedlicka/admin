<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\PivotDial;
use AdamJedlicka\Admin\Fields\Text;
use AdamJedlicka\Admin\FieldCollection;
use AdamJedlicka\Admin\Fields\BelongsTo;
use AdamJedlicka\Admin\Http\Requests\RelationshipRequest;

class BelongsToManyController extends Controller
{
    public function index(RelationshipRequest $request)
    {
        $field = $request->resource()->getFields()->named($request->relationship);

        return (new PivotDial($request->resource(), $request->relationship()))
            ->withPivot($field->getFields());
    }

    public function create(RelationshipRequest $request)
    {
        $field = $request->resource()->getFields()->named($request->relationship);

        $fields = new FieldCollection([
            BelongsTo::make(
                $request->relatedResource()->name(),
                $request->relationship,
                $request->resource()
            ),
        ]);

        return [
            'fields' => $fields->merge($field->getFields()),
        ];
    }
}
