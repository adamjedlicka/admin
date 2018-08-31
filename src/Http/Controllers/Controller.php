<?php

namespace AdamJedlicka\Luna\Http\Controllers;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
}
