<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use AdamJedlicka\Luna\Create;
use AdamJedlicka\Luna\Http\Requests\CreateRequest;

class CreateController extends Controller
{
    public function __invoke(CreateRequest $request)
    {
        return $request->resource()
            ->onlyFieldsFor('edit');
    }
}
