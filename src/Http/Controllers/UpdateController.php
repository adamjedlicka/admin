<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function __invoke(string $resource, $key)
    {
        $resource = $this->getResource($resource);

        request()->validate($resource->getUpdateRules());

        $model = $resource->model()::findOrFail($key);

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
