<?php

namespace AdamJedlicka\Luna\Support;

use Illuminate\Support\Str;
use AdamJedlicka\Luna\Resource;
use Symfony\Component\Finder\Finder;
use Illuminate\Database\Eloquent\Model;

class Resources
{
    /**
     * Returns resource for given name
     *
     * @param string $name Case insensitive name of the resource
     * @return \AdamJedlicka\Luna\Resource
     */
    public function forName(string $name) : Resource
    {
        $namespace = app()->getNamespace();

        $files = (new Finder)->in(app_path(config('luna.directory')))
            ->files()->name("/$name/i");

        foreach ($files as $file) {
            $path = Str::after($file->getPathname(), app_path() . DIRECTORY_SEPARATOR);
            $path = str_replace(DIRECTORY_SEPARATOR, '\\', $path);
            $path = str_replace('.php', '', $path);
            $path = $namespace . $path;

            return new $path;
        }
    }

    /**
     * Returns resource for given model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \AdamJedlicka\Luna\Resource
     */
    public function forModel(Model $model) : Resource
    {
        $name = (new \ReflectionClass($model))->getShortName();

        $resource = $this->forName($name);
        $resource->setModel($model);

        return $resource;
    }
}
