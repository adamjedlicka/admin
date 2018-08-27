<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;
use AdamJedlicka\Admin\Http\Requests\UpdateRequest;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        $resource = $request->resource();

        request()->validate($resource->getUpdateRules());

        $model = $resource->getModel();

        DB::transaction(function () use ($resource, $model) {
            $resource->getFields('edit')
                ->onlyFor('edit')
                ->each(function ($field) use ($model) {
                    $field->persist($model, request($field->getName()));
                });

            $model->saveOrFail();
        });

        return response()->json([
            'status' => 'success',
            'key' => $model->getKey(),
        ]);
    }
}
