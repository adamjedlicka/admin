<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use function AdamJedlicka\Admin\Support\array_depth;

abstract class Resource
{
    /**
     * Fully qualified name of the coresponding model
     *
     * @var string|null
     */
    protected $model = null;

    /**
     * Name of attribute used for title
     *
     * @var string|null
     */
    protected $title = null;

    /**
     * Instance of coresponding model
     *
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $modelInstance = null;

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
        return $this->title
            ? $this->{$this->title}
            : $this->name() . ' ' . $this->getKey();
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
        return $this->model ?? config('admin.models.namespace') . '\\' . $this->name();
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
     * Field by which resources are sorted by default
     *
     * @return string
     */
    public function sortBy() : string
    {
        return 'id';
    }

    /**
     * Order by which resources are sorted by default
     *
     * @return string
     */
    public function sortOrder() : string
    {
        return 'asc';
    }

    /**
     * Returns filtered out fields without panels
     *
     * @param string|null $view
     * @return \Illuminate\Support\Collection
     */
    public function getFields(? string $view = null) : Collection
    {
        return collect($this->fields())
            ->map(function ($field) {
                if ($field instanceof Panel) {
                    return $field->fields();
                } else {
                    return $field;
                }
            })
            ->flatten()
            ->filter(function (Field $field) use ($view) {
                return $view == null ? : $field->isVisibleOn($view);
            })
            ->each(function (Field $field) {
                $field->compute($this);
            })
            ->values();
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

    public function __get($name)
    {
        return $this->modelInstance->__get($name);
    }
}
