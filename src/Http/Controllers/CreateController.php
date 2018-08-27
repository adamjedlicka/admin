<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Create;
use AdamJedlicka\Admin\Http\Requests\CreateRequest;

class CreateController extends Controller
{
    public function __invoke(CreateRequest $request)
    {
        return $request->resource()
            ->onlyFieldsFor('edit');
    }
}
