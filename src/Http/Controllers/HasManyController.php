<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Facades\ResourceService;
use AdamJedlicka\Admin\Serializers\IndexSerializer;

class HasManyController extends Controller
{
    public function index(string $name, $key, string $relationship)
    {
        $resource = ResourceService::getResourceFromName($name);
        $model = $resource->fullyQualifiedModelName()::findOrFail($key);
        $query = $model->{$relationship}();
        $relatedResource = ResourceService::getResourceFromModel(
            $query->getRelated()
        );

        return new IndexSerializer($relatedResource, $query);
    }
}
