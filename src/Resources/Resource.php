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

    public function __construct()
    {
        $this->modelName = $this->modelName();
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
     * Returns the fully qualified name of the model.
     * By default generated from the name of the current resource.
     *
     * @return string
     */
    public function modelName() : string
    {
        return (new \ReflectionClass($this))->getName();
    }
}
