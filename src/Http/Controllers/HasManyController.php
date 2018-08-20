<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Facades\ResourceService;
use AdamJedlicka\Admin\Serializers\IndexSerializer;

class HasManyController extends Controller
{
    public function index(string $name, $key, string $relationship)
    {
        $resource = ResourceService::getResourceFromName($name);
        $model = $resource->model()::findOrFail($key);
        $query = $model->{$relationship}();
        $relatedResource = ResourceService::getResourceFromModel(
            $query->getRelated()
        );

        /**
         * We don't want to show related field because it always
         * contains the same value - title of the current model.
         */
        $relatedFieldname = $resource->getField($relationship)
            ->getRelatedField()->getName();

        return (new IndexSerializer($relatedResource, $query))
            ->exceptFields($relatedFieldname);
    }
}
