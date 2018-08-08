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
     */
    public static function make(string $displayName, ? string $field = null)
    {
        $self = new static($displayName, $field);
        return $self;
    }

    public function jsonSerialize()
    {
        return [
            'type' => (new \ReflectionClass($this))->getShortName(),
            'name' => $this->name,
            'field' => $this->field,
        ];
    }
}
