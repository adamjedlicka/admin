<?php

namespace AdamJedlicka\Luna\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \AdamJedlicka\Luna\Resource forName(string $name)
 * @method static \AdamJedlicka\Luna\Resource forModel(\Illuminate\Database\Eloquent\Model $model)
 */
class Resources extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'luna.resources';
    }
}
