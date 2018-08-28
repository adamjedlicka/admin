<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Http\Requests\ViewRequest;

class ShowController extends Controller
{
    public function __invoke(ViewRequest $request)
    {
        return $request->resource()
            ->onlyFieldsFor('detail');
    }
}
