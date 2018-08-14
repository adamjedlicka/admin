<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

abstract class Field implements Arrayable
{
    /**
     * Display name of the field
     *
     * @var string
     */
    protected $displayName;

    /**
     * Name of the field
     *
     * @var string
     */
    protected $name = null;

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
        $this->displayName = $displayName;

        if (is_null($options)) {
            $this->name = Str::snake($displayName);
        } else if (is_string($options)) {
            $this->name = $options;
        } else if (is_callable($options)) {
            $this->name = Str::snake($displayName);
            $this->callable = $options;

            $this->hideFromEdit();
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
     * Meta attributes for the field
     *
     * @return mixed
     */
    protected function meta()
    {
        return null;
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
        if (array_search($this->name, $model->getHidden()) !== false) {
            return null;
        }

        return $model->getAttribute($this->name);
    }

    public function persist(Model $model, $value)
    {
        $model->setAttribute($this->getName(), $value);
    }

    public function toArray()
    {
        return [
            'type' => (new \ReflectionClass($this))->getShortName(),
            'name' => $this->name,
            'displayName' => $this->displayName,
            'visibleOn' => $this->visibleOn,
            'sortable' => $this->sortable,
            'meta' => $this->meta(),
        ];
    }

    /**
     * Is the field computed?
     *
     * @return bool
     */
    public function isComputed() : bool
    {
        return $this->callable !== null;
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
     * Name getter
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
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
