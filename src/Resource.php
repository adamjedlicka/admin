<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;

abstract class Resource implements Arrayable
{
    /**
     * Fully qualified name of the coresponding model
     *
     * @var string
     */
    public static $model;

    /**
     * Instance of coresponding model
     *
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $modelInstance = null;

    /**
     * Limits fields to single view type
     *
     * @var string
     */
    protected $onlyFieldsFor = null;

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
     * Creates new instance of associated model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
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
     * @return \AdamJedlicka\Admin\FieldCollection
     */
    public function getFields() : FieldCollection
    {
        return (new FieldCollection($this->fields()))
            ->each(function (Field $field) {
                $field->setResource($this);
                $field->setModel($this->modelInstance);
            });
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
            ->toArray();
    }

    public function getPolicies() : array
    {
        $user = auth()->user();
        $model = $this->getModel();

        return [
            'view' => $user->can('view', $model),
            'create' => $user->can('create', $model),
            'update' => $user->can('update', $model),
            'delete' => $user->can('delete', $model),
        ];
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
        return (new $this::$model)->getKeyName();
    }

    /**
     * Limits fields to single view type
     *
     * @return self
     */
    public function onlyFieldsFor(string $view) : self
    {
        $this->onlyFieldsFor = $view;

        return $this;
    }

    public function toArray()
    {
        return [
            'name' => $this->name(),
            'key' => $this->getModel() ? $this->getModel()->getKey() : null,
            'pluralName' => $this->pluralName(),
            'title' => $this->title(),

            'policies' => $this->getPolicies(),
            'fields' => $this->getFields()->filter(function (Field $field) {
                return $this->onlyFieldsFor === null || $field->isVisibleOn($this->onlyFieldsFor);
            }),
        ];
    }

    public function __get($name)
    {
        if (!$this->modelInstance) return null;

        return $this->modelInstance->__get($name);
    }
}
