<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Create;

class CreateController extends Controller
{
    public function __invoke(string $resource)
    {
        $resource = $this->getResource($resource);

        return (new Create($resource->getFields('edit')))
            ->title('Create')
            ->storeUrl("/api/resources/{$resource->name()}");
    }
}
