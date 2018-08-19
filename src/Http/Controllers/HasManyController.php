<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\ResourceService;
use AdamJedlicka\Admin\Serializers\IndexSerializer;

class HasManyController extends Controller
{
    /**
     * @var \AdamJedlicka\Admin\ResourceService
     */
    public $service;

    public function __construct(ResourceService $service)
    {
        $this->service = $service;
    }

    public function index(string $name, $key, string $relationship)
    {
        $resource = $this->service->getResourceFromName($name);
        $model = $resource->model()::findOrFail($key);
        $query = $model->{$relationship}();
        $relatedResource = $this->service->getResourceFromModel(
            $query->getRelated()
        );

        return (new IndexSerializer($relatedResource, $query))
            ->exceptFields('user'); // TODO : Cant be fixed
    }
}
