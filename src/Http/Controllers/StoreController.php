<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;
use AdamJedlicka\Admin\Http\Requests\StoreReqeust;

class StoreController extends Controller
{
    public function __invoke(StoreReqeust $request)
    {
        $resource = $request->resource();

        request()->validate($resource->getCreationRules());

        $model = $resource->newModel();

        DB::transaction(function () use ($resource, $model) {
            $resource->getFields()
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
