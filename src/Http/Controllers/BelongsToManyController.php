<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\PivotDial;
use AdamJedlicka\Admin\Fields\Text;
use AdamJedlicka\Admin\PivotResource;
use AdamJedlicka\Admin\FieldCollection;
use AdamJedlicka\Admin\Fields\BelongsTo;
use AdamJedlicka\Admin\Http\Requests\AttachRequest;
use AdamJedlicka\Admin\Http\Requests\RelationshipRequest;

class BelongsToManyController extends Controller
{
    public function index(RelationshipRequest $request)
    {
        $field = $request->resource()->getFields()->named($request->relationship);

        return (new PivotDial($request->resource(), $request->relationship()))
            ->withPivot($field->getFields());
    }

    public function create(AttachRequest $request)
    {
        return (new PivotResource($request));
    }

    public function store(AttachRequest $request)
    {
        $request->validate([
            $request->relationship => 'required',
        ]);

        $request->relationship()->attach(
            $request->get($request->relationship),
            $request->except($request->relationship)
        );

        return response()->json([
            'status' => 'success',
        ]);
    }
}
