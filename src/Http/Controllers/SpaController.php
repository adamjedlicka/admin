<?php

namespace AdamJedlicka\Admin\Http\Controllers;

class SpaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        return view('admin::index');
    }
}
