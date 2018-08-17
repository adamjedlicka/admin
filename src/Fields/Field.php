<?php

namespace AdamJedlicka\Admin\Fields;

use Illuminate\Support\Str;
use AdamJedlicka\Admin\Resource;
use Illuminate\Support\Facades\Log;
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
     * Validation rules
     *
     * @var bool
     */
    protected $rules = [];

    /**
     * Meta attributes
     *
     * @var array
     */
    protected $meta = [];

    /**
     * Value of the field
     *
     * @var mixed
     */
    protected $value = null;

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
     * Computes value and metadata of the field
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     * @return self
     */
    public function compute(Resource $resource) : self
    {
        $modeName = $resource->fullyQualifiedModelName();
        $model = $resource->model ?? new $modeName;

        $this->prepare($resource, $model);

        if ($info = $this->metaInfo($resource)) {
            $this->meta['info'] = $info;
        }

        if ($resource->model) {
            $this->value = $this->retrieve($resource->model);

            if ($value = $this->metaValue($resource, $resource->model)) {
                $this->meta['value'] = $value;
            }
        }

        return $this;
    }

    /**
     * Prepare the field for computations
     */
    protected function prepare(Resource $resource, Model $model)
    {
        //
    }

    /**
     * Meta attributes for the field info
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     * @return mixed
     */
    protected function metaInfo(Resource $resource)
    {
        return null;
    }

    /**
     * Meta attributes for the field value
     *
     * @param \AdamJedlicka\Admin\Resource $resource
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    protected function metaValue(Resource $resource, Model $model)
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
        if ($this->options != null) {
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
        if (is_callable($this->options)) {
            return call_user_func($this->options, $model);
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
     * Rules getter
     *
     * @return array
     */
    public function getRules() : array
    {
        return $this->rules;
    }

    /**
     * Meta getter
     *
     * @return mixed
     */
    public function getMeta() : array
    {
        return $this->meta;
    }

    /**
     * Value getter
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
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
            'visibleOn' => $this->visibleOn,
            'isSortable' => $this->isSortable(),
            'isPanel' => $this->isPanel(),

            // Computed properties
            'meta' => $this->getMeta(),
            'value' => $this->getValue(),
        ];
    }
}
