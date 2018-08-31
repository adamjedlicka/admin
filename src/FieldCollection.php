<?php

namespace AdamJedlicka\Luna;

use Illuminate\Support\Collection;
use AdamJedlicka\Luna\Fields\Field;

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
     * @return \AdamJedlicka\Luna\Fields\Field
     */
    public function named(string $name) : Field
    {
        return $this->filter(function (Field $field) use ($name) {
            return $field->getName() == $name;
        })->first();
    }

    public function onlyFor(string $view) : self
    {
        return $this->filter(function (Field $field) use ($view) {
            return $field->isVisibleOn($view);
        })->values();
    }

    public function clone()
    {
        return $this->map(function ($field) {
            return clone $field;
        });
    }
}
