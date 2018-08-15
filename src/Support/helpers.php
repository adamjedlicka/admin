<?php

namespace AdamJedlicka\Admin\Support;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;

function get_metadata_from_file($filename)
{
    $contents = file_get_contents($filename);
    $namespace = null;
    $class = null;

    $readingNamespace = false;
    $readingClass = false;

    foreach (token_get_all($contents) as $token) {
        if (is_array($token) && $token[0] == T_NAMESPACE) {
            $readingNamespace = true;
        }

        if (is_array($token) && $token[0] == T_CLASS) {
            $readingClass = true;
        }

        if ($readingNamespace) {
            if (is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
                $namespace .= $token[1];
            } else if ($token === ';') {
                $readingNamespace = false;
            }
        }

        if ($readingClass) {
            if (is_array($token) && $token[0] == T_STRING) {
                $class = $token[1];

                break;
            }
        }
    }

    return [
        'namespace' => $namespace,
        'class' => $class,
    ];
}

function get_class_from_file($filename)
{
    $metadata = get_metadata_from_file($filename);

    return $metadata['namespace'] ? $metadata['namespace'] . '\\' . $metadata['class'] : $metadata['class'];
}

function get_namespace_from_file($filename)
{
    $metadata = get_metadata_from_file($filename);

    return $metadata['namespace'];
}

function get_resource_from_name(string $name) : Resource
{
    $fileName = Str::studly($name) . '.php';
    $path = app_path(config('admin.directory') . '/Resources');

    $class = get_class_from_file($path . '/' . $fileName);

    return new $class;
}

function get_resource_from_model(Model $model) : Resource
{
    $name = (new \ReflectionClass($model))->getShortName();

    $resource = get_resource_from_name($name);
    $resource->setModel($model);

    return $resource;
}
