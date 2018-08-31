<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use AdamJedlicka\Luna\Dial;
use AdamJedlicka\Luna\Http\Requests\IndexRequest;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        return (new Dial($request->resource()));
    }
}
