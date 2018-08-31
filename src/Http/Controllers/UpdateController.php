<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use AdamJedlicka\Luna\Facades\Models;
use AdamJedlicka\Luna\Http\Requests\UpdateRequest;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        $resource = $request->resource();

        request()->validate($resource->getUpdateRules());

        $model = Models::persist($resource->getModel());

        return response()->json([
            'status' => 'success',
            'key' => $model->getKey(),
        ]);
    }
}
