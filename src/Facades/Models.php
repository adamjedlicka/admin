<?php

namespace AdamJedlicka\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed persist(\Illuminate\Database\Eloquent\Model $model)
 */
class Models extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'admin.models';
    }
}
