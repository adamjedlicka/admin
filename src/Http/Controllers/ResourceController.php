<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Routing\Controller;
use AdamJedlicka\Admin\Resources\ListSerializer;

class ResourceController extends Controller
{
    public function index()
    {
        return new ListSerializer();
    }
}
