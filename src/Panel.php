<?php

namespace AdamJedlicka\Admin;

use AdamJedlicka\Admin\Fields\Field;
use Illuminate\Contracts\Support\Arrayable;

class Panel implements Arrayable
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

    public function toArray()
    {
        return [
            'displayName' => $this->displayName,
            'fields' => $this->fields,
        ];
    }
}
