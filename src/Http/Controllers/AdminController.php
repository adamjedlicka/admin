<?php

namespace AdamJedlicka\Admin\Http\Controllers;

class AdminController extends Controller
{
    public function resources()
    {
        $resources = [];

        foreach (glob(app_path(config('admin.directory') . '/Resources/' . '*\.php')) as $file) {
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
