<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Http\Requests\DetailRequest;

class ShowController extends Controller
{
    public function __invoke(DetailRequest $request)
    {
        return $request->resource()
            ->onlyFieldsFor('detail');
    }
}
