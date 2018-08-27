<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Facades\DB;
use AdamJedlicka\Admin\Facades\Models;
use AdamJedlicka\Admin\Http\Requests\StoreReqeust;

class StoreController extends Controller
{
    public function __invoke(StoreReqeust $request)
    {
        $resource = $request->resource();

        request()->validate($resource->getCreationRules());

        $model = Models::persist($resource->newModel());

        return response()->json([
            'status' => 'success',
            'key' => $model->getKey(),
        ]);
    }
}
