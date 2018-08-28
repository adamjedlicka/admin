<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\PivotDial;
use AdamJedlicka\Admin\Fields\Text;
use AdamJedlicka\Admin\Http\Requests\RelationshipRequest;

class BelongsToManyController extends Controller
{
    public function index(RelationshipRequest $request)
    {
        $field = $request->resource()->getFields()->named($request->relationship);

        return (new PivotDial($request->relatedResource()))
            ->query($request->relationship())
            ->withPivot($field->getFields());
    }
}
