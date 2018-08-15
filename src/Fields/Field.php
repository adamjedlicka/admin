<?php

namespace AdamJedlicka\Admin\Fields;

use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

abstract class Field implements Arrayable, JsonSerializable
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
            $this->name = $this->resolveName($displayName);
        } else if (is_string($options)) {
            $this->name = $options;
        } else if (is_callable($options)) {
            $this->name = $this->resolveName($displayName);
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
     * Retrieves the value
     *
     * @param Model $model Coresponding model
     * @return mixed
     */
    public function retrieve(Model $model)
    {
        if ($this->callable) {
            return call_user_func($this->callable, $model);
        }

        if (array_search($this->name, $model->getHidden()) !== false) {
            return null;
        }

        return $model->getAttribute($this->name);
    }

    /**
     * Persists the value to model
     *
     * @param Model $model Coresponding model
     * @param mixed $value Value to be persisted
     */
    public function persist(Model $model, $value)
    {
        $model->setAttribute($this->getName(), $value);
    }

    /**
     * Resolves the field name from display name
     *
     * @param string $displayName
     * @return string
     */
    protected function resolveName(string $displayName) : string
    {
        return Str::snake($displayName);
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

    public function jsonSerialize()
    {
        return $this->toArray();
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
