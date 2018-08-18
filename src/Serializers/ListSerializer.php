<?php

namespace AdamJedlicka\Admin\Serializers;

use JsonSerializable;
use function AdamJedlicka\Admin\Support\get_class_from_file;

class ListSerializer implements JsonSerializable
{
    public function jsonSerialize()
    {
        $resources = [];

        foreach (glob(app_path(config('admin.directory') . '/Resources/' . '*\.php')) as $file) {
            $resourceClass = get_class_from_file($file);
            $resource = new $resourceClass;

            $resources[] = [
                'name' => $resource->name(),
                'pluralName' => $resource->pluralName(),
            ];
        }

        return $resources;
    }
}
