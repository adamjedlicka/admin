<?php

namespace AdamJedlicka\Admin\Resources;

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
    public abstract function fields() : array;

    public function name() : string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Returns the fully qualified name of the model.
     * By default generated from the name of the current resource.
     *
     * @return string
     */
    protected function modelName() : string
    {
        return (new \ReflectionClass($this))->getName();
    }
}
