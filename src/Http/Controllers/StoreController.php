<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function __invoke(string $resource)
    {
        $resource = $this->getResource($resource);

        request()->validate($resource->getCreationRules());

        $model = $resource->model()::make();

        DB::transaction(function () use ($resource, $model) {
            $resource->getFields('edit')
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
