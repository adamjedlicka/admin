<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;
use AdamJedlicka\Admin\Http\Requests\IndexRequest;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $resource = $request->resource();

        return (new Dial($resource->getFields('index'), $resource->indexQuery()))
            ->detailUrl("/resources/{$resource->name()}/\${{$resource->getKeyName()}}")
            ->editUrl("/resources/{$resource->name()}/\${{$resource->getKeyName()}}/edit")
            ->deleteUrl("/api/resources/{$resource->name()}/\${{$resource->getKeyName()}}");
    }
}
