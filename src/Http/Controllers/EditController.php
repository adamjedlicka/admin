<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use AdamJedlicka\Luna\Http\Requests\EditRequest;


class EditController extends Controller
{
    public function __invoke(EditRequest $request)
    {
        return $request->resource()
            ->onlyFieldsFor('edit');
    }
}
