<?php

namespace AdamJedlicka\Luna;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AdamJedlicka\Luna\Fields\Field;
use AdamJedlicka\Luna\Fields\HasMany;
use Illuminate\Database\Eloquent\Model;
use AdamJedlicka\Luna\Traits\Authorizes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;

abstract class Resource implements Arrayable
{
    use Authorizes;

    /**
     * Fully qualified name of the coresponding model
     *
     * @var string
     */
    public static $model;

    /**
     * Array of fields that are searchable
     *
     * @var array|null
     */
    public static $search = null;

    /**
     * Instance of coresponding model
     *
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $modelInstance = null;

    /**
     * Limits fields to be displayed
     *
     * @var \AdamJedlicka\Luna\FieldCollection
     */
    protected $onlyFields = null;

    /**
     * Limits fields to single view type
     *
     * @var string
     */
    protected $onlyFieldsFor = null;

    /**
     * Extra fields to display. For eaxmple for pivot table
     *
     * @var \AdamJedlicka\Luna\FieldCollection
     */
    protected $extraFields = null;

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
        return $this->name() . ($this->getModel() ? (' ' . $this->getModel()->getKey()) : '');
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
        return $this::$model::query();
    }

    /**
     * Returns filtered out fields without panels
     *
     * @return \AdamJedlicka\Luna\FieldCollection
     */
    public function getFields() : FieldCollection
    {
        return (new FieldCollection($this->fields()))
            ->merge($this->extraFields)
            ->each(function (Field $field) {
                $field->setResource($this);
                if ($this->modelInstance) $field->setModel($this->modelInstance);
            });
    }

    /**
     * Returns related field
     *
     * @return \AdamJedlicka\Luna\Fields\Field
     */
    public function getRelatedFieldOf(string $field) : Field
    {
        return $this->getFields()
            ->named($field)
            ->getRelatedField($this);
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
        return [
            'view' => $this->authorizeIfPolicyExists('view', $this->getModel()),
            'create' => $this->authorizeIfPolicyExists('create', $this::$model),
            'update' => $this->authorizeIfPolicyExists('update', $this->getModel()),
            'delete' => $this->authorizeIfPolicyExists('delete', $this->getModel()),
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
     * Sets which fields are displayed
     *
     * @param \AdamJedlicka\Luna\FieldCollection
     * @return self
     */
    public function onlyFields(FieldCollection $fields) : self
    {
        $this->onlyFields = $fields;

        return $this;
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

    /**
     * Sets extra fields to display. For example for pivot table.
     *
     * @return self
     */
    public function extraFields(FieldCollection $fields) : self
    {
        /**
         * We need to clone fields so we won't interfere with other references
         */
        $this->extraFields = $fields->clone();

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
            'fields' => $this->getFields()
                ->filter(function (Field $field) {
                    return $this->onlyFields ? array_search($field->getName(), $this->onlyFields->names()) !== false : true;
                })
                ->filter(function (Field $field) {
                    return $this->onlyFieldsFor === null || $field->isVisibleOn($this->onlyFieldsFor);
                })
                ->values(),
        ];
    }

    public function __get($name)
    {
        if (!$this->modelInstance) return null;

        return $this->modelInstance->__get($name);
    }
}
