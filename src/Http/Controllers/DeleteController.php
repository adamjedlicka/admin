<?php

namespace AdamJedlicka\Admin\Http\Controllers;

class DeleteController extends Controller
{
    public function __invoke(string $resource, $key)
    {
        $resource = $this->getResource($resource);
        $model = $resource->model()::findOrFail($key);

        $model->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
