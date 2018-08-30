<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\PivotDial;
use AdamJedlicka\Admin\Fields\Text;
use AdamJedlicka\Admin\PivotResource;
use AdamJedlicka\Admin\FieldCollection;
use AdamJedlicka\Admin\Fields\BelongsTo;
use AdamJedlicka\Admin\Http\Requests\RelationshipRequest;
use AdamJedlicka\Admin\Http\Requests\BelongsToManyStoreRequest;
use AdamJedlicka\Admin\Http\Requests\BelongsToManyCreateRequest;
use AdamJedlicka\Admin\Http\Requests\BelongsToManyDeleteRequest;

class BelongsToManyController extends Controller
{
    public function index(RelationshipRequest $request)
    {
        $field = $request->resource()->getFields()->named($request->relationship);

        return (new PivotDial($request->resource(), $request->relationship()))
            ->withPivot($field->getFields());
    }

    public function create(BelongsToManyCreateRequest $request)
    {
        return PivotResource::fromRequest($request);
    }

    public function store(BelongsToManyStoreRequest $request)
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

    public function delete(BelongsToManyDeleteRequest $request)
    {
        $request->relationship()->detach($request->relationshipKey);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
