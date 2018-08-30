<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\PivotDial;
use AdamJedlicka\Admin\Fields\Text;
use AdamJedlicka\Admin\PivotResource;
use AdamJedlicka\Admin\FieldCollection;
use AdamJedlicka\Admin\Fields\BelongsTo;
use AdamJedlicka\Admin\Http\Requests\AttachRequest;
use AdamJedlicka\Admin\Http\Requests\DetachRequest;
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
        return PivotResource::fromRequest($request);
    }

    public function store(AttachRequest $request)
    {
        $field = $request->resource()->getFields()->named($request->relationship);

        $request->validate(array_merge([
            $request->relationship => ['required'],
        ], $field->getPivotCreationRules()));

        $request->relationship()->attach(
            $request->get($request->relationship),
            $request->except($request->relationship)
        );

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete(DetachRequest $request)
    {
        $request->relationship()->detach($request->relationshipKey);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
