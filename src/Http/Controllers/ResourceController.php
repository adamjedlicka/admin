<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Routing\Controller;

class ResourceController extends Controller
{
    public function index()
    {
        foreach (glob(app_path('Admin/Resources/' . '*\.php')) as $file) {
            $class = get_class_from_file($file);
            $inst = new $class;
            $resources[] = $inst->name();
        }

        return $resources;
    }
}
