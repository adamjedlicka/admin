<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use AdamJedlicka\Luna\PivotDial;
use AdamJedlicka\Luna\Fields\Text;
use AdamJedlicka\Luna\PivotResource;
use AdamJedlicka\Luna\FieldCollection;
use AdamJedlicka\Luna\Fields\BelongsTo;
use AdamJedlicka\Luna\Http\Requests\RelationshipRequest;
use AdamJedlicka\Luna\Http\Requests\BelongsToManyStoreRequest;
use AdamJedlicka\Luna\Http\Requests\BelongsToManyCreateRequest;
use AdamJedlicka\Luna\Http\Requests\BelongsToManyDeleteRequest;
use AdamJedlicka\Luna\Http\Requests\BelongsToManyUpdateRequest;

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

    public function edit(BelongsToManyUpdateRequest $request)
    {
        return PivotResource::fromRequest($request);
    }

    public function update(BelongsToManyUpdateRequest $request)
    {
        $field = $request->resource()->getFields()->named($request->relationship);

        $request->validate(array_merge([
            $request->relationship => ['required'],
        ], $field->getPivotCreationRules()));

        $request->relationship()->updateExistingPivot(
            $request->relationshipKey,
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
