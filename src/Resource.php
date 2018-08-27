<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class Resource
{
    /**
     * Instance of coresponding model
     *
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $modelInstance = null;

    /**
     * Fully qualified name of the coresponding model
     *
     * @var string
     */
    public static $model;

    /**
     * Definition of fields
     *
     * @return array
     */
    abstract public function fields();

    /**
     * Title of the model to be displayed
     *
     * @return string
     */
    public function title()
    {
        return $this->name() . ' ' . $this->getModel()->getKey();
    }

    /**
     * Name of the resource
     *
     * @return string
     */
    public function name() : string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Plural version of the resource name
     *
     * @return string
     */
    public function pluralName() : string
    {
        return Str::plural($this->name());
    }

    /**
     * Returns fully qualified class name of the coresponding model
     *
     * @return string
     */
    public function model() : string
    {
        return config('admin.models.namespace') . '\\' . $this->name();
    }

    public function newModel() : Model
    {
        return new static::$model;
    }

    /**
     * Default query used to build index data
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indexQuery() : Builder
    {
        return $this->model()::query();
    }

    /**
     * Returns filtered out fields without panels
     *
     * @param string|null $view
     * @return \AdamJedlicka\Admin\FieldCollection
     */
    public function getFields(? string $view = null) : FieldCollection
    {
        return (new FieldCollection($this->fields()))
            ->flatten()
            ->filter(function (Field $field) use ($view) {
                return $view === null ? : $field->isVisibleOn($view);
            })
            ->each(function (Field $field) {
                $field->setResource($this);
            })
            ->values();
    }

    /**
     * Returns single field of specified name
     *
     * @param string $name
     * @return \AdamJedlicka\Admin\Fields\Field
     */
    public function getField(string $name) : Field
    {
        return $this->getFields()
            ->filter(function (Field $field) use ($name) {
                return $field->getName() == $name;
            })
            ->first();
    }

    /**
     * Returns computed array of all creation rules
     *
     * @return array
     */
    public function getCreationRules() : array
    {
        return $this->getFields()
            ->mapWithKeys(function (Field $field) {
                return [$field->getName() => $field->getCreationRules()];
            })
            ->mapWithKeys(function ($rules, $key) {
                if (array_depth($rules) == 1) {
                    return [$key => $rules];
                }

                // Transform array into dot notation
                foreach ($rules as $ruleKey => $rule) {
                    $result[$key . '.' . $ruleKey] = $rule;
                }

                return $result;
            })
            ->toArray();
    }

    /**
     * Returns computed array of all update rules
     *
     * @return array
     */
    public function getUpdateRules() : array
    {
        return $this->getFields()
            ->mapWithKeys(function (Field $field) {
                return [$field->getName() => $field->getUpdateRules()];
            })
            ->mapWithKeys(function ($rules, $key) {
                if (array_depth($rules) == 1) {
                    return [$key => $rules];
                }

                // Transform array into dot notation
                foreach ($rules as $ruleKey => $rule) {
                    $result[$key . '.' . $ruleKey] = $rule;
                }

                return $result;
            })
            ->toArray();
    }

    /**
     * Model instance setter
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function setModel(Model $model)
    {
        $this->modelInstance = $model;
    }

    /**
     * Model instance getter
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getModel() : ? Model
    {
        return $this->modelInstance;
    }

    /**
     * Returns keyName of the coresponding model
     *
     * @return string
     */
    public function getKeyName() : string
    {
        $model = $this->model();

        return (new $model)->getKeyName();
    }

    public function __get($name)
    {
        if (!$this->modelInstance) return null;

        return $this->modelInstance->__get($name);
    }
}
