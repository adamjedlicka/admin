<?php

namespace AdamJedlicka\Admin\Resources;

use JsonSerializable;

class ListSerializer implements JsonSerializable
{
    /**
     * @var \AdamJedlicka\Admin\Resources\Resource
     */
    private $resource;

    public function jsonSerialize()
    {
        foreach (glob(app_path('Admin/Resources/' . '*\.php')) as $file) {
            $resourceClass = get_class_from_file($file);
            $resource = new $resourceClass;

            $resources[] = [
                'name' => (new \ReflectionClass($resource))->getShortName(),
                'displayName' => $resource->displayName(),
            ];
        }

        return $resources;
    }
}
