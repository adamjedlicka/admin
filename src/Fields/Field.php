<?php

namespace AdamJedlicka\Admin\Fields;

use JsonSerializable;
use Illuminate\Support\Str;

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
    protected $field;

    /**
     * Is this field visible on the index view?
     *
     * @var boolean
     */
    protected $indexVisible = false;

    /**
     * Is this field visible on the detail view?
     *
     * @var boolean
     */
    protected $detailVisible = true;

    /**
     * Size of the field in the index view
     *
     * @var string
     */
    protected $indexSize = 'normal';

    /**
     * Indicates whether it is possible to sort using this field
     *
     * @var boolean
     */
    protected $sortable = false;

    private function __construct(string $displayName, ? string $field = null)
    {
        $this->name = $displayName;
        $this->field = $field ?? Str::snake($this->name);
    }

    /**
     * Named constructor for fluent syntax
     *
     * @param string $displayName Display name of the field
     * @param string|null $field Name fo the field in database
     * @return self
     */
    public static function make(string $displayName, ? string $field = null)
    {
        $self = new static($displayName, $field);
        return $self;
    }

    /**
     * Show field on index view
     *
     * @return self
     */
    public function showOnIndex()
    {
        $this->indexVisible = true;
        return $this;
    }

    /**
     * Show field on detail view
     *
     * @return self
     */
    public function showOnDetail()
    {
        $this->detailVisible = true;
        return $this;
    }

    /**
     * Hide field from index view
     *
     * @return self
     */
    public function hideFromIndex()
    {
        $this->indexVisible = false;
        return $this;
    }

    /**
     * Hide field from detail view
     *
     * @return self
     */
    public function hideFromDetail()
    {
        $this->detailVisible = false;
        return $this;
    }

    /**
     * Makes the field sortable in index views
     *
     * @return self
     */
    public function sortable()
    {
        $this->sortable = true;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'type' => (new \ReflectionClass($this))->getShortName(),
            'name' => $this->name,
            'field' => $this->field,
            'indexVisible' => $this->indexVisible,
            'detailVisible' => $this->detailVisible,
            'indexSize' => $this->indexSize,
            'sortable' => $this->sortable,
        ];
    }
}
