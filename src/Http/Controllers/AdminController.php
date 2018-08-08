<?php

namespace AdamJedlicka\Admin\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin::index');
    }
}
