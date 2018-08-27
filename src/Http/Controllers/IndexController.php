<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;
use AdamJedlicka\Admin\Http\Requests\IndexRequest;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        return (new Dial($request->resource()));
    }
}
