<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

abstract class Field implements Arrayable
{
    /**
     * @var \AdamJedlicka\Admin\Resource|null
     */
    protected $resource = null;

    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $model = null;

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
     * If value of field is already set, it cannot be changed.
     * For example in edit or when field has default value.
     *
     * @var bool
     */
    protected $cannotBeChanged = false;

    /**
     * Default value for the field
     *
     * @var mixed
     */
    protected $default = null;

    /**
     * Represents field on the pivot table?
     *
     * @var bool
     */
    protected $isPivot = false;

    /**
     * Constructor. Use static Field::make method instead.
     *
     * @param string $displayName
     * @param mixed $options
     * @param \Illuminate\Http\Resources\Json\Resource|null $resource
     */
    protected function __construct(string $displayName, $options = null, $resource = null)
    {
        $this->type = (new \ReflectionClass($this))->getShortName();
        $this->displayName = $displayName;

        if (is_null($resource)) {
            $this->resource = $options;
            $this->name = $this->resolveName($displayName);
        } else if (is_string($options)) {
            $this->resource = $resource;
            $this->name = $options;
        } else if (is_callable($options)) {
            $this->resource = $resource;
            $this->options = $options;
            $this->name = $this->resolveName($displayName);

            $this->hideFromEdit();
        }
    }

    /**
     * Named constructor for fluent syntax
     *
     * @param mixed $args
     * @return self
     */
    public static function make(...$args) : self
    {
        if (count($args) < 3) {
            foreach (debug_backtrace() as $trace) {
                if (!array_has($trace, 'class')) continue;

                $class = $trace['class'];

                if (is_subclass_of($class, Resource::class)) {
                    $args[] = new $class;
                    break;
                }
            }
        }

        return new static(...$args);
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

    /**
     * If value of field is already set, it cannot be changed.
     * For example in edit or when field has default value.
     *
     * @param bool $cannotBeChanged
     * @return self
     */
    public function cannotBeChanged(bool $cannotBeChanged = true) : self
    {
        $this->cannotBeChanged = $cannotBeChanged;

        return $this;
    }

    /**
     * Sets the defualt value
     *
     * @param mixed $default
     * @return self
     */
    public function default($default) : self
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Represents field on pivot table?
     *
     * @return self
     */
    public function isPivot(bool $isPivot = true) : self
    {
        $this->isPivot = $isPivot;

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
        return $model->getAttribute($this->name);
    }

    /**
     * Returns the value of field for certain model
     *
     * @param Model $model
     * @return mixed
     */
    protected function value(Model $model)
    {
        if ($this->isPivot) {
            $model = $model->pivot;
        }

        if (is_callable($this->options)) {
            return call_user_func($this->options, $model);
        }

        if (array_search($this->name, $model->getHidden()) !== false) {
            return null;
        }

        return $this->retrieve($model);
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
     * Returns extra information which gets send to frontend API
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     * @return array
     */
    public function exports(Resource $resource)
    {
        return [];
    }

    /**
     * Returns custom meta information about the field for current model
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function meta(Resource $resource, Model $model)
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
     * Sets the coresponding resource
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Sets the coresponding model
     *
     * @param \Illuminate\Database\Eloquent\Model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
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
     * CannotBeChanged getter
     *
     * @return bool
     */
    public function isUnchangeable() : bool
    {
        return $this->cannotBeChanged;
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
     * Default value getter
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
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
        return [
            'type' => $this->getType(),
            'name' => $this->getName(),
            'displayName' => $this->getDisplayName(),
            'default' => $this->getDefault(),
            'isSortable' => $this->isSortable(),
            'isUnchangeable' => $this->isUnchangeable(),
            'isPanel' => $this->isPanel(),

            'exports' => $this->resource ? $this->exports($this->resource) : [],
            'meta' => $this->model ? $this->meta($this->resource, $this->model) : null,
            'value' => $this->model ? $this->value($this->model) : null,
            'modelKey' => $this->model ? $this->model->getKey() : null,
        ];
    }
}
