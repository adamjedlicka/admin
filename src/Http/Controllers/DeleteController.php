<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use AdamJedlicka\Luna\Http\Requests\DeleteRequest;

class DeleteController extends Controller
{
    public function __invoke(DeleteRequest $request)
    {
        $model = $request->resource()->getModel();

        $model->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
