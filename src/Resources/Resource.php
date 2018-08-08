<?php

namespace AdamJedlicka\Admin\Resources;

use Illuminate\Support\Str;

abstract class Resource
{
    /**
     * Name of the model class this resource is prepresenting
     *
     * @var string
     */
    protected $modelName;

    /**
     * Namespace of the model class this resource is representing
     *
     * @var string
     */
    protected $modelNamespace;

    public function __construct()
    {
        $this->modelName = $this->modelName();
        $this->modelNamespace = $this->modelNamespace();
    }

    /**
     * Definition of fields
     *
     * @return array
     */
    abstract public function fields() : array;

    /**
     * Display name of the resource. By default plural version of the model name
     *
     * @return string
     */
    public function displayName() : string
    {
        return Str::plural((new \ReflectionClass($this))->getShortName());
    }

    /**
     * Returns the name of the model.
     * By default generated from the name of the current resource.
     *
     * @return string
     */
    public function modelName() : string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Returns the namespace of the model.
     * By default generated from the name of the current resource.
     *
     * @return string
     */
    public function modelNamespace() : string
    {
        return get_namespace_from_file(app_path($this->modelName . '.php'));
    }
}
