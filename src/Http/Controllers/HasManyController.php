<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use AdamJedlicka\Luna\Dial;
use AdamJedlicka\Luna\Create;
use AdamJedlicka\Luna\Facades\Resources;
use AdamJedlicka\Luna\Http\Requests\RelationshipRequest;

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
