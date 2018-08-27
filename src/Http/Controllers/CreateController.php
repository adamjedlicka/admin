<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Create;

class CreateController extends Controller
{
    public function __invoke(string $resource)
    {
        return $this->getResource($resource)
            ->onlyFieldsFor('edit');
    }
}
