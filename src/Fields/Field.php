<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

abstract class Field implements Arrayable
{
    /**
     * Type of the field
     *
     * @var string
     */
    protected $type;

    /**
     * Name of the field
     *
     * @var string
     */
    protected $name;

    /**
     * Display name of the field
     *
     * @var string
     */
    protected $displayName;

    /**
     * Options
     *
     * @var mixed
     */
    protected $options = null;

    /**
     * Array of views where the field is visible
     *
     * @var array
     */
    protected $visibleOn = [];

    /**
     * Is the field sortable?
     *
     * @var bool
     */
    protected $sortable = false;

    /**
     * Is the field its own panel?
     *
     * @var bool
     */
    protected $panel = false;

    /**
     * Validation rules for model creation
     *
     * @var array
     */
    protected $creationRules = [];

    /**
     * Validation rules for model update
     *
     * @var array
     */
    protected $updateRules = [];

    /**
     * List of relationships to eager load
     *
     * @var array
     */
    protected $load = [];

    /**
     * Optional array of values that gets send to API
     *
     * @var array
     */
    protected $export = [];

    /**
     * Constructor. Use static Field::make method instead.
     *
     * @param string $displayName
     * @param mixed $options
     */
    protected function __construct(string $displayName, $options = null)
    {
        $this->type = (new \ReflectionClass($this))->getShortName();
        $this->displayName = $displayName;

        if (is_null($options)) {
            $this->name = $this->resolveName($displayName);
        } else if (is_string($options)) {
            $this->name = $options;
        } else {
            $this->name = $this->resolveName($displayName);
            $this->options = $options;

            $this->hideFromEdit();
        }
    }

    /**
     * Named constructor for fluent syntax
     *
     * @param string $displayName
     * @param mixed $options
     * @return self
     */
    public static function make(string $displayName, $options = null) : self
    {
        return new static($displayName, $options);
    }

    /**
     * Sets up the field
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     */
    public function boot(Resource $resource)
    {

    }

    /**
     * Show field on index view
     *
     * @return self
     */
    public function showOnIndex() : self
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
    public function showOnDetail() : self
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
    public function showOnEdit() : self
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
    public function hideFromIndex() : self
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
    public function hideFromDetail() : self
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
    public function hideFromEdit() : self
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
    public function sortable() : self
    {
        if ($this->options != null) {
            return $this;
        }

        $this->sortable = true;

        return $this;
    }

    /**
     * Set validation rules
     *
     * @param array $rules
     * @return self
     */
    public function rules(...$rules) : self
    {
        $this->creationRules = $rules;
        $this->updateRules = $rules;

        return $this;
    }

    /**
     * Set validation rules for model creation
     *
     * @param array $rules
     * @return self
     */
    public function creationRules(...$rules) : self
    {
        $this->creationRules = $rules;

        return $this;
    }

    /**
     * Set validation rules for model update
     *
     * @param array $rules
     * @return self
     */
    public function updateRules(...$rules) : self
    {
        $this->updateRules = $rules;

        return $this;
    }

    public function load(...$load) : self
    {
        $this->load = array_merge($this->load, $load);

        return $this;
    }

    /**
     * Set extra values to be exported to API
     *
     * @param array $export
     * @return self
     */
    public function export(array $export) : self
    {
        $this->export = array_merge($this->export, $export);

        return $this;
    }

    public function options($options) : self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Retrieves the model from the database
     *
     * @param Model $model Coresponding model
     * @return mixed
     */
    public function retrieve(Model $model)
    {
        if (is_callable($this->options)) {
            return call_user_func($this->options, $model);
        }

        if (array_search($this->name, $model->getHidden()) !== false) {
            return null;
        }

        return $model->getAttribute($this->name);
    }

    /**
     * Persists the model to the databse
     *
     * @param Model $model Coresponding model
     * @param mixed $value Value to be persisted
     */
    public function persist(Model $model, $value)
    {
        $model->setAttribute($this->getName(), $value);
    }

    /**
     * Returns custom meta information about the field
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     * @return mixed
     */
    public function meta(Resource $resource)
    {
        return null;
    }

    /**
     * Returns custom value that can be used on the frontend
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function value(Resource $resource, Model $model)
    {
        return null;
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

    /**
     * Type getter
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * DisplayName getter
     *
     * @return string
     */
    public function getDisplayName() : string
    {
        return $this->displayName;
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
     * Creation rules getter
     *
     * @return array
     */
    public function getCreationRules() : array
    {
        return $this->creationRules;
    }

    /**
     * Update rules getter
     *
     * @return array
     */
    public function getUpdateRules() : array
    {
        return $this->updateRules;
    }

    /**
     * Visible on getter
     *
     * @return array
     */
    public function getVisibleOn() : array
    {
        return $this->visibleOn;
    }

    /**
     * Is the field computed?
     *
     * @return bool
     */
    public function isComputed() : bool
    {
        return is_callable($this->options);
    }

    /**
     * Is the field visible on given field?
     *
     * @param string $view
     * @return bool
     */
    public function isVisibleOn(string $view) : bool
    {
        return array_search($view, $this->visibleOn) !== false;
    }

    /**
     * Is the field sortable?
     *
     * @return bool
     */
    public function isSortable() : bool
    {
        return $this->sortable;
    }

    /**
     * Is the field its own panel?
     *
     * @return bool
     */
    public function isPanel() : bool
    {
        return $this->panel;
    }

    public function toArray()
    {
        return array_merge([
            'type' => $this->getType(),
            'name' => $this->getName(),
            'displayName' => $this->getDisplayName(),
            'visibleOn' => $this->getVisibleOn(),
            'isSortable' => $this->isSortable(),
            'isPanel' => $this->isPanel(),
        ], $this->export);
    }
}
