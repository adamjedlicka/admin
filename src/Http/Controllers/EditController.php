<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Http\Requests\EditRequest;


class EditController extends Controller
{
    public function __invoke(EditRequest $request)
    {
        return $request->resource()
            ->onlyFieldsFor('edit');
    }
}
