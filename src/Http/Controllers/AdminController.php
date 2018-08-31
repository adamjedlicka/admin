<?php

namespace AdamJedlicka\Luna\Http\Controllers;

class AdminController extends Controller
{
    public function resources()
    {
        $resources = [];

        foreach (glob(app_path(config('luna.directory') . '/Resources/' . '*\.php')) as $file) {
            $resourceClass = get_class_from_file($file);
            $resource = new $resourceClass;

            $resources[] = [
                'name' => $resource->name(),
                'pluralName' => $resource->pluralName(),
            ];
        }

        return $resources;
    }
}
