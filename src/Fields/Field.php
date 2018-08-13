<?php

namespace AdamJedlicka\Admin\Fields;

use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

abstract class Field implements JsonSerializable
{
    /**
     * Display name of the field
     *
     * @var string
     */
    protected $displayName;

    /**
     * Name of the field in the database
     *
     * @var string
     */
    protected $field = null;

    /**
     * Callback for computed field
     *
     * @var callable
     */
    protected $callable = null;

    /**
     *  Indicates on what views field is visible
     *
     * @var array
     */
    protected $visibleOn = [];

    /**
     * Size of the field in the index view
     *
     * @var string
     */
    protected $indexSize = 'normal';

    /**
     * Indicates whether it is possible to sort using this field
     *
     * @var bool
     */
    protected $sortable = false;

    /**
     * Validation rules
     *
     * @var bool
     */
    protected $rules = [];

    private function __construct(string $displayName, $options = null)
    {
        $this->name = $displayName;

        if (is_null($options)) {
            $this->field = Str::snake($displayName);
        } else if (is_string($options)) {
            $this->field = $options;
        } else if (is_callable($options)) {
            $this->field = Str::snake($displayName);
            $this->callable = $options;
            $this->editVisible = false;
        }
    }

    /**
     * Named constructor for fluent syntax
     *
     * @param string $displayName Display name of the field
     * @param string|null $field Name fo the field in database
     * @return self
     */
    public static function make(string $displayName, $options = null)
    {
        $self = new static($displayName, $options);
        return $self;
    }

    /**
     * Show field on index view
     *
     * @return self
     */
    public function showOnIndex()
    {
        if (!in_array('index', $this->visibleOn)) {
            $this->visibleOn[] = 'index';
        }
        return $this;
    }

    /**
     * Show field on detail view
     *
     * @return self
     */
    public function showOnDetail()
    {
        if (!in_array('detail', $this->visibleOn)) {
            $this->visibleOn[] = 'detail';
        }
        return $this;
    }


    /**
     * Show field on edit view
     *
     * @return self
     */
    public function showOnEdit()
    {
        if (!in_array('edit', $this->visibleOn)) {
            $this->visibleOn[] = 'edit';
        }
        return $this;
    }

    /**
     * Hide field from index view
     *
     * @return self
     */
    public function hideFromIndex()
    {
        if (($key = array_search('index', $this->visibleOn)) !== false) {
            array_splice($this->visibleOn, $key, 1);
        }
        return $this;
    }

    /**
     * Hide field from detail view
     *
     * @return self
     */
    public function hideFromDetail()
    {
        if (($key = array_search('detail', $this->visibleOn)) !== false) {
            array_splice($this->visibleOn, $key, 1);
        }
        return $this;
    }

    /**
     * Hide field from edit view
     *
     * @return self
     */
    public function hideFromEdit()
    {
        if (($key = array_search('edit', $this->visibleOn)) !== false) {
            array_splice($this->visibleOn, $key, 1);
        }
        return $this;
    }

    /**
     * Makes the field sortable in index views
     *
     * @return self
     */
    public function sortable()
    {
        if ($this->callable != null) {
            return $this;
        }

        $this->sortable = true;
        return $this;
    }

    /**
     * Set validation rules
     *
     * @return self
     */
    public function rules(...$rules)
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Set width on the index view to one half
     *
     * @return self
     */
    public function oneHalf()
    {
        $this->indexSize = 'oneHalf';
        return $this;
    }

    public function resolve(Model $model)
    {
        return $this->callable
            ? $this->resolveCallable($model)
            : $this->resolveAttribute($model);
    }

    protected function resolveCallable(Model $model)
    {
        return call_user_func($this->callable, $model);
    }

    protected function resolveAttribute(Model $model)
    {
        return $model->getAttribute($this->field);
    }

    public function jsonSerialize()
    {
        return [
            'type' => (new \ReflectionClass($this))->getShortName(),
            'name' => $this->name,
            'field' => $this->field,
            'visibleOn' => $this->visibleOn,
            'indexSize' => $this->indexSize,
            'sortable' => $this->sortable,
        ];
    }

    /**
     * Returns whether the field is dynamic in width on index view
     *
     * @return bool
     */
    public function isDynamic() : bool
    {
        switch ($this->indexSize) {
            case 'oneHalf':
                return true;

            default:
                return false;
                break;
        }
    }

    /**
     * Indicates whether the field is visible on the given view
     *
     * @return bool
     */
    public function isVisibleOn(string $view) : bool
    {
        return array_search($view, $this->visibleOn) !== false;
    }

    /**
     * Field getter
     *
     * @return string
     */
    public function getField() : string
    {
        return $this->field;
    }

    /**
     * Rules getter
     *
     * @return array
     */
    public function getRules() : array
    {
        return $this->rules;
    }
}
