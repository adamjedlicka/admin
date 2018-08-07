<?php

namespace AdamJedlicka\Admin\Http\Controllers;

use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin::index');
    }
}
