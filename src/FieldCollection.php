<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Collection;
use AdamJedlicka\Admin\Fields\Field;

class FieldCollection extends Collection
{
    /**
     * Returns array of field names
     *
     * @return array
     */
    public function names()
    {
        $names = [];
        foreach ($this->items as $item) {
            $names[] = $item->getName();
        }

        return $names;
    }

    /**
     * Returns field of specified name
     *
     * @param string $name
     * @return \AdamJedlicka\Admin\Fields\Field
     */
    public function named(string $name) : Field
    {
        return $this->filter(function ($field) use ($name) {
            return $field->getName() == $name;
        })->first();
    }
}
