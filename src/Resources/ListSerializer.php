<?php

namespace AdamJedlicka\Admin\Resources;

use JsonSerializable;

class ListSerializer implements JsonSerializable
{
    public function jsonSerialize()
    {
        foreach (glob(app_path(config('admin.directory') . '/Resources/' . '*\.php')) as $file) {
            $resourceClass = get_class_from_file($file);
            $resource = new $resourceClass;

            $resources[] = [
                'name' => $resource->name(),
                'displayName' => $resource->displayName(),
            ];
        }

        return $resources;
    }
}
