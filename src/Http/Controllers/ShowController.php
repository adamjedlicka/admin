<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use AdamJedlicka\Luna\Http\Requests\ViewRequest;

class ShowController extends Controller
{
    public function __invoke(ViewRequest $request)
    {
        return $request->resource()
            ->onlyFieldsFor('detail');
    }
}
