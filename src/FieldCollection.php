<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Collection;

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
}
