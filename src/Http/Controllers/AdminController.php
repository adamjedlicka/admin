<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use AdamJedlicka\Admin\Serializers\ListSerializer;

class AdminController extends Controller
{
    public function resources()
    {
        return new ListSerializer();
    }
}
