<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Dial;
use AdamJedlicka\Admin\Create;
use AdamJedlicka\Admin\Facades\Resources;
use AdamJedlicka\Admin\Http\Requests\RelationshipRequest;

class HasManyController extends Controller
{
    public function index(RelationshipRequest $request)
    {
        $resource = $request->resource();
        $relatedResource = $request->relatedResource();

        return (new Dial($relatedResource))
            ->query($request->relationship())
            ->hideFields($resource->getRelatedFieldOf($request->relationship));
    }
}
