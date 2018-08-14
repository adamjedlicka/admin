<?php

namespace AdamJedlicka\Admin;

use JsonSerializable;

class Panel implements JsonSerializable
{
    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var array
     */
    protected $fields;

    public function __construct(string $displayName, array $fields)
    {
        $this->displayName = $displayName;
        $this->fields = $fields;
    }

    public function fields() : array
    {
        return $this->fields;
    }

    public function jsonSerialize() : array
    {
        return [
            'displayName' => $this->displayName,
            'fields' => $this->fields,
        ];
    }
}
