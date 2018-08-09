<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Locates and returns resource based on name
     *
     * @return \AdamJedlicka\Admin\Resource
     */
    public function getResourceFromName(string $name)
    {
        $fileName = Str::studly($name) . '.php';
        $path = app_path(config('admin.directory') . '/Resources');

        $class = get_class_from_file($path . '/' . $fileName);

        return new $class;
    }
}
