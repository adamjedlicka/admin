<?php

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
