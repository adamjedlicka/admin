<?php

namespace AdamJedlicka\Admin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \AdamJedlicka\Admin\Resource forName(string $name)
 * @method static \AdamJedlicka\Admin\Resource forModel(\Illuminate\Database\Eloquent\Model $model)
 */
class Resources extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'admin.resources';
    }
}
