<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    public function getResourceFromName(string $name)
    {
        $fileName = Str::studly($name) . '.php';
        $path = app_path(config('admin.directory') . '/Resources');

        $class = get_class_from_file($path . '/' . $fileName);

        return new $class;
    }
}
